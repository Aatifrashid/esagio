export default function toothChart2D(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const toothData = options.teeth || {};
    const readonly = options.readonly || false;
    const notation = options.notation || 'fdi';

    const conditionColours = {
        missing: '#94a3b8', decayed: '#b91c1c', to_extract: '#dc2626',
        root_canal_done: '#7c3aed', root_canal_needed: '#9333ea',
        crowned: '#eab308', implant_existing: '#64748b', implant_planned: '#2563eb',
        veneer_existing: '#10b981', veneer_planned: '#059669',
        filling: '#6b7280', fractured: '#ef4444', sensitive: '#f59e0b',
        bridge_abutment: '#0891b2', bridge_pontic: '#06b6d4',
        denture: '#8b5cf6', periodontal: '#ea580c',
        abscess: '#dc2626', impacted: '#78716c', erupting: '#a3e635',
    };

    const upperRight = [18, 17, 16, 15, 14, 13, 12, 11];
    const upperLeft = [21, 22, 23, 24, 25, 26, 27, 28];
    const lowerRight = [48, 47, 46, 45, 44, 43, 42, 41];
    const lowerLeft = [31, 32, 33, 34, 35, 36, 37, 38];

    function getToothColour(toothNum) {
        const data = toothData[toothNum];
        if (!data || !data.conditions || data.conditions.length === 0) return '#e5e7eb';
        const priority = ['missing', 'to_extract', 'implant_planned', 'decayed', 'root_canal_needed', 'fractured'];
        for (const code of priority) {
            if (data.conditions.includes(code)) return conditionColours[code] || '#e5e7eb';
        }
        return conditionColours[data.conditions[0]] || '#e5e7eb';
    }

    function getLabel(num) {
        if (notation === 'fdi') return num.toString();
        return num.toString();
    }

    function renderRow(teeth, y, label) {
        let html = '';
        teeth.forEach((num, i) => {
            const x = 30 + i * 55;
            const colour = getToothColour(num);
            const conditions = toothData[num]?.conditions || [];
            const isMissing = conditions.includes('missing');
            const cursor = readonly ? 'default' : 'pointer';

            html += `<g class="tooth-group" data-tooth="${num}" style="cursor:${cursor}">
                <rect x="${x}" y="${y}" width="45" height="50" rx="6"
                    fill="${colour}" stroke="#d1d5db" stroke-width="1.5"
                    ${isMissing ? 'stroke-dasharray="4"' : ''} />
                <text x="${x + 22}" y="${y + 30}" text-anchor="middle"
                    font-size="12" font-weight="600" fill="${colour === '#e5e7eb' ? '#374151' : '#ffffff'}">
                    ${getLabel(num)}
                </text>
                ${conditions.length > 0 ? `<circle cx="${x + 38}" cy="${y + 7}" r="4" fill="#ef4444" />` : ''}
            </g>`;
        });

        html += `<text x="15" y="${y + 30}" text-anchor="middle"
            font-size="10" fill="#9ca3af" font-weight="500">${label}</text>`;

        return html;
    }

    const svgWidth = 470;
    const svgHeight = 260;

    let svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${svgWidth} ${svgHeight}"
        class="w-full max-w-lg mx-auto" style="font-family: Inter, system-ui, sans-serif">
        <text x="${svgWidth / 2}" y="18" text-anchor="middle" font-size="11" fill="#6b7280" font-weight="500">Upper Jaw</text>
        ${renderRow(upperRight, 25, 'R')}
        ${renderRow(upperLeft, 25, 'L')}
        <line x1="30" y1="85" x2="${30 + 8 * 55}" y2="85" stroke="#d1d5db" stroke-width="1" stroke-dasharray="4" />
        <text x="${svgWidth / 2}" y="105" text-anchor="middle" font-size="11" fill="#6b7280" font-weight="500">Lower Jaw</text>
        ${renderRow(lowerRight, 115, 'R')}
        ${renderRow(lowerLeft, 115, 'L')}
    </svg>`;

    container.innerHTML = svg;

    if (!readonly) {
        container.querySelectorAll('.tooth-group').forEach(group => {
            group.addEventListener('click', () => {
                const toothNum = parseInt(group.dataset.tooth);
                container.dispatchEvent(new CustomEvent('tooth-selected', {
                    detail: { toothNumber: toothNum, data: toothData[toothNum] || {} },
                    bubbles: true,
                }));
            });
        });
    }

    return {
        updateTooth: (toothNum, conditions) => {
            toothData[toothNum] = { ...toothData[toothNum], conditions };
            const group = container.querySelector(`[data-tooth="${toothNum}"]`);
            if (!group) return;
            const rect = group.querySelector('rect');
            if (rect) {
                const colour = getToothColour(toothNum);
                rect.setAttribute('fill', colour);
                if (conditions.includes('missing')) {
                    rect.setAttribute('stroke-dasharray', '4');
                } else {
                    rect.removeAttribute('stroke-dasharray');
                }
            }
        },
        destroy: () => { container.innerHTML = ''; },
    };
}

window.toothChart2D = toothChart2D;
