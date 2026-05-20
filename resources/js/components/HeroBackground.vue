<template>
  <div
    ref="root"
    class="absolute inset-0 overflow-hidden select-none"
    style="pointer-events:none; z-index:0;"
  >
    <!-- base white -->
    <div class="absolute inset-0" style="background:#fafafa;"></div>

    <!-- ambient orbs -->
    <div class="orb-abs orb-g1" :style="{ transform: `translate3d(${px(0.30)}px,${py(0.22)}px,0)` }"></div>
    <div class="orb-abs orb-r1" :style="{ transform: `translate3d(${px(-0.28)}px,${py(-0.20)}px,0)` }"></div>
    <div class="orb-abs orb-g2" :style="{ transform: `translate3d(${px(0.15)}px,${py(0.11)}px,0)` }"></div>

    <!-- green waves top-left -->
    <svg class="absolute overflow-visible" style="top:-30px;left:-45px;width:70%;height:72%;will-change:transform;" viewBox="0 0 820 620" fill="none"
      :style="{ transform: `translate3d(${px(0.55)}px,${py(0.38)}px,0)` }">
      <path class="wl wg1" d="M-140 480 C60 430 200 498 400 438 C590 378 700 322 860 382" stroke="#006B4F" stroke-width="1.55" stroke-opacity="0.15" fill="none" stroke-linecap="round"/>
      <path class="wl wg2" d="M-140 395 C80 348 215 412 435 358 C640 304 745 248 880 302" stroke="#006B4F" stroke-width="1.10" stroke-opacity="0.11" fill="none" stroke-linecap="round"/>
      <path class="wl wg3" d="M-140 545 C50 498 188 562 388 508 C575 454 680 398 845 452" stroke="#0F8A65" stroke-width="0.90" stroke-opacity="0.08" fill="none" stroke-linecap="round"/>
      <path class="wl wg4" d="M-140 255 C105 212 228 268 458 222 C668 176 772 128 900 178" stroke="#006B4F" stroke-width="1.00" stroke-opacity="0.07" fill="none" stroke-linecap="round"/>
      <path class="wl wg5" d="M-140 172 C88 138 212 182 422 148 C620 114 722 68 875 112" stroke="#0F8A65" stroke-width="0.75" stroke-opacity="0.06" fill="none" stroke-linecap="round"/>
      <path class="wl wg6" d="M-140 318 C72 278 196 332 408 286 C602 240 706 192 862 240" stroke="#006B4F" stroke-width="0.60" stroke-opacity="0.05" fill="none" stroke-linecap="round"/>
      <path class="wl wg7" d="M-140 88 C68 62 186 98 388 74 C572 50 672 12 828 52" stroke="#0F8A65" stroke-width="0.55" stroke-opacity="0.04" fill="none" stroke-linecap="round"/>
    </svg>

    <!-- red waves bottom-right -->
    <svg class="absolute overflow-visible" style="bottom:-30px;right:-45px;width:70%;height:72%;will-change:transform;" viewBox="0 0 820 620" fill="none"
      :style="{ transform: `translate3d(${px(-0.48)}px,${py(-0.34)}px,0)` }">
      <g transform="rotate(180,410,310)">
        <path class="wl wr1" d="M-140 480 C60 430 200 498 400 438 C590 378 700 322 860 382" stroke="#C62828" stroke-width="1.45" stroke-opacity="0.12" fill="none" stroke-linecap="round"/>
        <path class="wl wr2" d="M-140 395 C80 348 215 412 435 358 C640 304 745 248 880 302" stroke="#C62828" stroke-width="1.00" stroke-opacity="0.09" fill="none" stroke-linecap="round"/>
        <path class="wl wr3" d="M-140 545 C50 498 188 562 388 508 C575 454 680 398 845 452" stroke="#C62828" stroke-width="0.80" stroke-opacity="0.07" fill="none" stroke-linecap="round"/>
        <path class="wl wr4" d="M-140 255 C105 212 228 268 458 222 C668 176 772 128 900 178" stroke="#C62828" stroke-width="0.85" stroke-opacity="0.06" fill="none" stroke-linecap="round"/>
        <path class="wl wr5" d="M-140 172 C88 138 212 182 422 148 C620 114 722 68 875 112" stroke="#C62828" stroke-width="0.65" stroke-opacity="0.05" fill="none" stroke-linecap="round"/>
      </g>
    </svg>

    <!-- scientific network top-right -->
    <svg class="absolute overflow-visible" style="top:-18px;right:-18px;width:47%;height:58%;will-change:transform;" viewBox="0 0 420 400" fill="none"
      :style="{ transform: `translate3d(${px(-0.72)}px,${py(0.52)}px,0)` }">
      <line x1="300" y1="45"  x2="368" y2="95"  stroke="#006B4F" stroke-width="0.70" stroke-opacity="0.22"/>
      <line x1="300" y1="45"  x2="245" y2="105" stroke="#006B4F" stroke-width="0.65" stroke-opacity="0.18"/>
      <line x1="300" y1="45"  x2="195" y2="65"  stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.15"/>
      <line x1="368" y1="95"  x2="385" y2="170" stroke="#006B4F" stroke-width="0.70" stroke-opacity="0.20"/>
      <line x1="368" y1="95"  x2="245" y2="105" stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.14"/>
      <line x1="245" y1="105" x2="315" y2="200" stroke="#006B4F" stroke-width="0.60" stroke-opacity="0.17"/>
      <line x1="245" y1="105" x2="178" y2="160" stroke="#006B4F" stroke-width="0.65" stroke-opacity="0.16"/>
      <line x1="245" y1="105" x2="195" y2="65"  stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.15"/>
      <line x1="385" y1="170" x2="315" y2="200" stroke="#006B4F" stroke-width="0.65" stroke-opacity="0.18"/>
      <line x1="385" y1="170" x2="348" y2="265" stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.14"/>
      <line x1="315" y1="200" x2="265" y2="235" stroke="#006B4F" stroke-width="0.60" stroke-opacity="0.19"/>
      <line x1="315" y1="200" x2="348" y2="265" stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.14"/>
      <line x1="195" y1="65"  x2="178" y2="160" stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.14"/>
      <line x1="265" y1="235" x2="178" y2="160" stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.13"/>
      <line x1="265" y1="235" x2="148" y2="240" stroke="#006B4F" stroke-width="0.50" stroke-opacity="0.12"/>
      <line x1="265" y1="235" x2="220" y2="300" stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.14"/>
      <line x1="348" y1="265" x2="392" y2="318" stroke="#006B4F" stroke-width="0.60" stroke-opacity="0.15"/>
      <line x1="348" y1="265" x2="265" y2="235" stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.14"/>
      <line x1="392" y1="318" x2="282" y2="325" stroke="#006B4F" stroke-width="0.50" stroke-opacity="0.13"/>
      <line x1="282" y1="325" x2="220" y2="300" stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.14"/>
      <line x1="148" y1="240" x2="220" y2="300" stroke="#006B4F" stroke-width="0.50" stroke-opacity="0.12"/>
      <line x1="178" y1="160" x2="148" y2="240" stroke="#006B4F" stroke-width="0.55" stroke-opacity="0.13"/>
      <!-- halos -->
      <circle cx="300" cy="45"  r="9"  fill="rgba(0,107,79,0.06)" class="nd-h" style="--d:0.0s"/>
      <circle cx="368" cy="95"  r="8"  fill="rgba(0,107,79,0.05)" class="nd-h" style="--d:0.5s"/>
      <circle cx="245" cy="105" r="11" fill="rgba(0,107,79,0.07)" class="nd-h" style="--d:0.9s"/>
      <circle cx="385" cy="170" r="8"  fill="rgba(0,107,79,0.05)" class="nd-h" style="--d:1.4s"/>
      <circle cx="315" cy="200" r="9"  fill="rgba(0,107,79,0.06)" class="nd-h" style="--d:0.7s"/>
      <circle cx="195" cy="65"  r="7"  fill="rgba(0,107,79,0.04)" class="nd-h" style="--d:2.1s"/>
      <circle cx="348" cy="265" r="8"  fill="rgba(0,107,79,0.05)" class="nd-h" style="--d:0.3s"/>
      <circle cx="265" cy="235" r="10" fill="rgba(0,107,79,0.07)" class="nd-h" style="--d:1.2s"/>
      <circle cx="178" cy="160" r="7"  fill="rgba(0,107,79,0.04)" class="nd-h" style="--d:2.5s"/>
      <circle cx="392" cy="318" r="7"  fill="rgba(0,107,79,0.04)" class="nd-h" style="--d:1.1s"/>
      <circle cx="282" cy="325" r="8"  fill="rgba(0,107,79,0.05)" class="nd-h" style="--d:1.8s"/>
      <circle cx="148" cy="240" r="7"  fill="rgba(0,107,79,0.04)" class="nd-h" style="--d:3.0s"/>
      <circle cx="220" cy="300" r="8"  fill="rgba(0,107,79,0.05)" class="nd-h" style="--d:2.2s"/>
      <!-- core dots -->
      <circle cx="300" cy="45"  r="2.8" fill="#006B4F" fill-opacity="0.42" class="nd-dot" style="--d:0.0s"/>
      <circle cx="368" cy="95"  r="2.2" fill="#006B4F" fill-opacity="0.36" class="nd-dot" style="--d:0.5s"/>
      <circle cx="245" cy="105" r="3.2" fill="#006B4F" fill-opacity="0.48" class="nd-dot" style="--d:0.9s"/>
      <circle cx="385" cy="170" r="2.2" fill="#006B4F" fill-opacity="0.34" class="nd-dot" style="--d:1.4s"/>
      <circle cx="315" cy="200" r="2.8" fill="#006B4F" fill-opacity="0.40" class="nd-dot" style="--d:0.7s"/>
      <circle cx="195" cy="65"  r="1.9" fill="#006B4F" fill-opacity="0.28" class="nd-dot" style="--d:2.1s"/>
      <circle cx="348" cy="265" r="2.2" fill="#006B4F" fill-opacity="0.34" class="nd-dot" style="--d:0.3s"/>
      <circle cx="265" cy="235" r="3.0" fill="#006B4F" fill-opacity="0.44" class="nd-dot" style="--d:1.2s"/>
      <circle cx="178" cy="160" r="1.9" fill="#006B4F" fill-opacity="0.27" class="nd-dot" style="--d:2.5s"/>
      <circle cx="392" cy="318" r="1.9" fill="#006B4F" fill-opacity="0.26" class="nd-dot" style="--d:1.1s"/>
      <circle cx="282" cy="325" r="2.2" fill="#006B4F" fill-opacity="0.33" class="nd-dot" style="--d:1.8s"/>
      <circle cx="148" cy="240" r="1.9" fill="#006B4F" fill-opacity="0.27" class="nd-dot" style="--d:3.0s"/>
      <circle cx="220" cy="300" r="2.2" fill="#006B4F" fill-opacity="0.33" class="nd-dot" style="--d:2.2s"/>
    </svg>

    <!-- floating particle canvas -->
    <canvas ref="cv" class="absolute inset-0" style="width:100%;height:100%;"></canvas>

    <!-- radial depth vignette -->
    <div class="absolute inset-0" style="background:radial-gradient(ellipse 74% 64% at 50% 46%,transparent 18%,rgba(250,250,250,0.06) 42%,rgba(250,250,250,0.38) 72%,rgba(250,250,250,0.72) 100%);"></div>

    <!-- bottom fade -->
    <div class="absolute bottom-0 left-0 right-0 h-44" style="background:linear-gradient(to top,#fafafa,transparent);"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const root = ref(null)
const cv   = ref(null)
const mx   = ref(0)
const my   = ref(0)
let _rawMx = 0, _rawMy = 0

const px = (s) => mx.value * s * 100
const py = (s) => my.value * s * 80

function onMouseMove(e) {
  const hero = root.value?.parentElement
  if (!hero) return
  const r = hero.getBoundingClientRect()
  _rawMx = (e.clientX - r.left) / r.width  - 0.5
  _rawMy = (e.clientY - r.top)  / r.height - 0.5
}

let rafLerp = null
function startLerp() {
  const tick = () => {
    const s = 0.055
    mx.value += (_rawMx - mx.value) * s
    my.value += (_rawMy - my.value) * s
    rafLerp = requestAnimationFrame(tick)
  }
  tick()
}

let canvasCleanup = null
function initCanvas() {
  const canvas = cv.value
  if (!canvas) return
  const ctx = canvas.getContext('2d')
  let W, H, pts, rafDraw

  const resize = () => {
    W = canvas.width  = canvas.offsetWidth  || window.innerWidth
    H = canvas.height = canvas.offsetHeight || window.innerHeight
    pts = Array.from({ length: 42 }, () => ({
      x:  Math.random() * W,
      y:  Math.random() * H,
      vx: (Math.random() - 0.5) * 0.24,
      vy: (Math.random() - 0.5) * 0.24,
      r:  Math.random() * 1.5 + 0.5,
      op: Math.random() * 0.18 + 0.06,
      g:  Math.random() > 0.38,
    }))
  }

  const draw = () => {
    ctx.clearRect(0, 0, W, H)
    for (const p of pts) {
      p.x += p.vx; p.y += p.vy
      if (p.x < 0) p.x = W; if (p.x > W) p.x = 0
      if (p.y < 0) p.y = H; if (p.y > H) p.y = 0
      ctx.beginPath()
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2)
      ctx.fillStyle = p.g
        ? `rgba(0,107,79,${p.op})`
        : `rgba(198,40,40,${+(p.op * 0.78).toFixed(3)})`
      ctx.fill()
    }
    rafDraw = requestAnimationFrame(draw)
  }

  resize()
  draw()
  const ro = new ResizeObserver(resize)
  ro.observe(canvas)
  return () => { cancelAnimationFrame(rafDraw); ro.disconnect() }
}

onMounted(() => {
  canvasCleanup = initCanvas()
  startLerp()
  const hero = root.value?.parentElement
  if (hero) hero.addEventListener('mousemove', onMouseMove, { passive: true })
})

onUnmounted(() => {
  if (canvasCleanup) canvasCleanup()
  cancelAnimationFrame(rafLerp)
  const hero = root.value?.parentElement
  if (hero) hero.removeEventListener('mousemove', onMouseMove)
})
</script>

<style scoped>
.orb-abs { position:absolute; border-radius:50%; will-change:transform; pointer-events:none; }

.orb-g1 {
  width:700px; height:700px; top:-200px; left:-160px;
  background:radial-gradient(circle,rgba(0,107,79,0.11) 0%,rgba(0,107,79,0.03) 45%,transparent 70%);
  filter:blur(3px);
  animation:orbBr1 11s ease-in-out infinite alternate;
}
.orb-r1 {
  width:580px; height:580px; bottom:-160px; right:-110px;
  background:radial-gradient(circle,rgba(198,40,40,0.10) 0%,rgba(198,40,40,0.02) 45%,transparent 70%);
  filter:blur(3px);
  animation:orbBr2 14s ease-in-out infinite alternate;
}
.orb-g2 {
  width:400px; height:400px; top:28%; left:40%;
  background:radial-gradient(circle,rgba(15,138,101,0.07) 0%,transparent 65%);
  filter:blur(4px);
  animation:orbBr3 9s ease-in-out infinite alternate;
}
@keyframes orbBr1 { 0%{opacity:.55} 100%{opacity:.95} }
@keyframes orbBr2 { 0%{opacity:.50} 100%{opacity:.88} }
@keyframes orbBr3 { 0%{opacity:.45} 100%{opacity:.82} }

.wl { will-change:transform; }
.wg1{animation:wvUp 11.0s 0.0s ease-in-out infinite alternate;}
.wg2{animation:wvUp 14.5s 1.3s ease-in-out infinite alternate;}
.wg3{animation:wvUp  9.5s 2.7s ease-in-out infinite alternate;}
.wg4{animation:wvUp 13.0s 0.6s ease-in-out infinite alternate;}
.wg5{animation:wvUp 16.5s 2.0s ease-in-out infinite alternate;}
.wg6{animation:wvUp 12.5s 3.4s ease-in-out infinite alternate;}
.wg7{animation:wvUp 18.0s 1.8s ease-in-out infinite alternate;}
.wr1{animation:wvUp 12.0s 0.4s ease-in-out infinite alternate;}
.wr2{animation:wvUp 10.5s 2.2s ease-in-out infinite alternate;}
.wr3{animation:wvUp 15.5s 0.8s ease-in-out infinite alternate;}
.wr4{animation:wvUp 13.5s 3.1s ease-in-out infinite alternate;}
.wr5{animation:wvUp 17.0s 1.5s ease-in-out infinite alternate;}
@keyframes wvUp { 0%{transform:translateY(0px)} 100%{transform:translateY(-22px)} }

.nd-h {
  transform-box:fill-box; transform-origin:center;
  animation:haloG 3.4s ease-in-out infinite;
  animation-delay:var(--d,0s);
}
@keyframes haloG { 0%,100%{opacity:.35;transform:scale(1)} 50%{opacity:1;transform:scale(1.6)} }

.nd-dot {
  transform-box:fill-box; transform-origin:center;
  animation:dotG 3.4s ease-in-out infinite;
  animation-delay:var(--d,0s);
}
@keyframes dotG { 0%,100%{fill-opacity:.20} 50%{fill-opacity:.58} }
</style>
