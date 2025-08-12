<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

interface Particle {
    id: number;
    x: number;
    y: number;
    size: number;
    speedX: number;
    speedY: number;
    opacity: number;
    color: string;
}

const canvasRef = ref<HTMLCanvasElement>();
const particles = ref<Particle[]>([]);
const animationId = ref<number>();

const colors = [
    'rgba(249, 115, 22, 0.3)', // orange
    'rgba(239, 68, 68, 0.3)',  // red
    'rgba(245, 158, 11, 0.3)', // yellow
    'rgba(251, 146, 60, 0.3)', // orange-400
];

const createParticle = (): Particle => ({
    id: Math.random(),
    x: Math.random() * window.innerWidth,
    y: Math.random() * window.innerHeight,
    size: Math.random() * 3 + 1,
    speedX: (Math.random() - 0.5) * 0.5,
    speedY: (Math.random() - 0.5) * 0.5,
    opacity: Math.random() * 0.5 + 0.1,
    color: colors[Math.floor(Math.random() * colors.length)],
});

const initParticles = () => {
    const count = Math.floor((window.innerWidth * window.innerHeight) / 20000);
    particles.value = Array.from({ length: count }, createParticle);
};

const animateParticles = () => {
    const canvas = canvasRef.value;
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    // Set canvas size
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Update and draw particles
    particles.value.forEach((particle) => {
        // Update position
        particle.x += particle.speedX;
        particle.y += particle.speedY;

        // Wrap around edges
        if (particle.x < 0) particle.x = canvas.width;
        if (particle.x > canvas.width) particle.x = 0;
        if (particle.y < 0) particle.y = canvas.height;
        if (particle.y > canvas.height) particle.y = 0;

        // Draw particle
        ctx.beginPath();
        ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
        ctx.fillStyle = particle.color;
        ctx.globalAlpha = particle.opacity;
        ctx.fill();
    });

    // Draw connections between nearby particles
    particles.value.forEach((particle1, i) => {
        particles.value.slice(i + 1).forEach((particle2) => {
            const dx = particle1.x - particle2.x;
            const dy = particle1.y - particle2.y;
            const distance = Math.sqrt(dx * dx + dy * dy);

            if (distance < 100) {
                ctx.beginPath();
                ctx.moveTo(particle1.x, particle1.y);
                ctx.lineTo(particle2.x, particle2.y);
                ctx.strokeStyle = 'rgba(249, 115, 22, 0.1)';
                ctx.lineWidth = 0.5;
                ctx.stroke();
            }
        });
    });

    animationId.value = requestAnimationFrame(animateParticles);
};

const handleResize = () => {
    initParticles();
};

onMounted(() => {
    initParticles();
    animateParticles();
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    if (animationId.value) {
        cancelAnimationFrame(animationId.value);
    }
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
    <canvas
        ref="canvasRef"
        class="fixed inset-0 pointer-events-none z-0"
        style="background: transparent;"
    ></canvas>
</template>
