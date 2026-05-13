export default function videoConsult(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const { roomUrl, token, userName, isClinic = false } = options;

    let callFrame = null;

    async function loadDailyScript() {
        if (window.DailyIframe) return;
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/@daily-co/daily-js';
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    async function join() {
        await loadDailyScript();

        callFrame = window.DailyIframe.createFrame(container, {
            showLeaveButton: true,
            showFullscreenButton: true,
            iframeStyle: {
                width: '100%',
                height: '100%',
                border: '0',
                borderRadius: '12px',
            },
        });

        callFrame.on('left-meeting', () => {
            container.dispatchEvent(new CustomEvent('consultation-ended', { bubbles: true }));
        });

        callFrame.on('error', (e) => {
            console.error('Daily.co error:', e);
            container.dispatchEvent(new CustomEvent('consultation-error', {
                detail: { error: e },
                bubbles: true,
            }));
        });

        await callFrame.join({
            url: roomUrl,
            token: token,
            userName: userName,
        });

        container.dispatchEvent(new CustomEvent('consultation-joined', { bubbles: true }));

        return callFrame;
    }

    function leave() {
        if (callFrame) {
            callFrame.leave();
            callFrame.destroy();
            callFrame = null;
        }
    }

    function toggleCamera() {
        if (callFrame) callFrame.setLocalVideo(!callFrame.localVideo());
    }

    function toggleMic() {
        if (callFrame) callFrame.setLocalAudio(!callFrame.localAudio());
    }

    function startRecording() {
        if (callFrame && isClinic) callFrame.startRecording();
    }

    function stopRecording() {
        if (callFrame && isClinic) callFrame.stopRecording();
    }

    return { join, leave, toggleCamera, toggleMic, startRecording, stopRecording };
}

window.videoConsult = videoConsult;
