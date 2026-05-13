import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

export default function toothChart3D(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const width = container.clientWidth;
    const height = options.height || 400;

    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0xf8fafc);

    const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
    camera.position.set(0, 8, 12);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.shadowMap.enabled = true;
    container.appendChild(renderer.domElement);

    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.maxPolarAngle = Math.PI * 0.85;
    controls.minDistance = 5;
    controls.maxDistance = 25;

    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
    scene.add(ambientLight);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(5, 10, 5);
    directionalLight.castShadow = true;
    scene.add(directionalLight);

    const fillLight = new THREE.DirectionalLight(0xffffff, 0.3);
    fillLight.position.set(-5, 5, -5);
    scene.add(fillLight);

    const teethMeshes = {};
    const toothData = options.teeth || {};
    const conditionColours = {
        missing: 0x94a3b8,
        decayed: 0xb91c1c,
        to_extract: 0xdc2626,
        root_canal_done: 0x7c3aed,
        root_canal_needed: 0x9333ea,
        crowned: 0xeab308,
        implant_existing: 0x64748b,
        implant_planned: 0x2563eb,
        veneer_existing: 0x10b981,
        veneer_planned: 0x059669,
        filling: 0x6b7280,
        fractured: 0xef4444,
        sensitive: 0xf59e0b,
    };

    const fdiTeeth = [
        18, 17, 16, 15, 14, 13, 12, 11,
        21, 22, 23, 24, 25, 26, 27, 28,
        48, 47, 46, 45, 44, 43, 42, 41,
        31, 32, 33, 34, 35, 36, 37, 38,
    ];

    function createToothGeometry(toothNum) {
        const position = toothNum % 10;
        const isMolar = position >= 6;
        const isPremolar = position >= 4 && position <= 5;
        const isCanine = position === 3;

        if (isMolar) {
            return new THREE.BoxGeometry(0.7, 0.5, 0.7);
        } else if (isPremolar) {
            return new THREE.BoxGeometry(0.5, 0.6, 0.5);
        } else if (isCanine) {
            return new THREE.ConeGeometry(0.25, 0.8, 8);
        } else {
            return new THREE.BoxGeometry(0.4, 0.7, 0.3);
        }
    }

    function getToothPosition(toothNum) {
        const quadrant = Math.floor(toothNum / 10);
        const position = toothNum % 10;
        const isUpper = quadrant <= 2;
        const isRight = quadrant === 1 || quadrant === 4;

        const xOffset = (position - 1) * 0.8;
        const x = isRight ? -xOffset - 0.4 : xOffset + 0.4;
        const y = isUpper ? 0.5 : -0.5;
        const z = 0;

        return new THREE.Vector3(x, y, z);
    }

    function getToothColour(toothNum) {
        const data = toothData[toothNum];
        if (!data || !data.conditions || data.conditions.length === 0) {
            return 0xe5e7eb;
        }
        const priorityOrder = ['missing', 'to_extract', 'implant_planned', 'decayed', 'root_canal_needed', 'fractured'];
        for (const code of priorityOrder) {
            if (data.conditions.includes(code)) {
                return conditionColours[code] || 0xe5e7eb;
            }
        }
        const firstCondition = data.conditions[0];
        return conditionColours[firstCondition] || 0xe5e7eb;
    }

    fdiTeeth.forEach(toothNum => {
        const geometry = createToothGeometry(toothNum);
        const colour = getToothColour(toothNum);
        const material = new THREE.MeshStandardMaterial({
            color: colour,
            roughness: 0.4,
            metalness: 0.1,
        });

        const mesh = new THREE.Mesh(geometry, material);
        const pos = getToothPosition(toothNum);
        mesh.position.copy(pos);
        mesh.castShadow = true;
        mesh.receiveShadow = true;
        mesh.userData = { toothNum, ...toothData[toothNum] };

        scene.add(mesh);
        teethMeshes[toothNum] = mesh;
    });

    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();
    let selectedTooth = null;

    renderer.domElement.addEventListener('click', (event) => {
        const rect = renderer.domElement.getBoundingClientRect();
        mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(Object.values(teethMeshes));

        if (intersects.length > 0) {
            const mesh = intersects[0].object;
            const toothNum = mesh.userData.toothNum;

            if (selectedTooth) {
                selectedTooth.material.emissive.setHex(0x000000);
            }

            mesh.material.emissive.setHex(0x333333);
            selectedTooth = mesh;

            container.dispatchEvent(new CustomEvent('tooth-selected', {
                detail: { toothNumber: toothNum, data: mesh.userData },
                bubbles: true,
            }));
        }
    });

    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }
    animate();

    const resizeObserver = new ResizeObserver(() => {
        const newWidth = container.clientWidth;
        camera.aspect = newWidth / height;
        camera.updateProjectionMatrix();
        renderer.setSize(newWidth, height);
    });
    resizeObserver.observe(container);

    const viewPresets = {
        anterior: () => { camera.position.set(0, 0, 12); controls.target.set(0, 0, 0); },
        posterior: () => { camera.position.set(0, 0, -12); controls.target.set(0, 0, 0); },
        occlusalUpper: () => { camera.position.set(0, 12, 0); controls.target.set(0, 0, 0); },
        occlusalLower: () => { camera.position.set(0, -12, 0); controls.target.set(0, 0, 0); },
        fullMouth: () => { camera.position.set(0, 8, 12); controls.target.set(0, 0, 0); },
    };

    return {
        setView: (preset) => viewPresets[preset]?.(),
        updateTooth: (toothNum, conditions) => {
            const mesh = teethMeshes[toothNum];
            if (!mesh) return;
            mesh.userData.conditions = conditions;
            toothData[toothNum] = { ...toothData[toothNum], conditions };
            mesh.material.color.setHex(getToothColour(toothNum));
        },
        getSelectedTooth: () => selectedTooth?.userData?.toothNum,
        destroy: () => {
            resizeObserver.disconnect();
            renderer.dispose();
            container.removeChild(renderer.domElement);
        },
    };
}

window.toothChart3D = toothChart3D;
