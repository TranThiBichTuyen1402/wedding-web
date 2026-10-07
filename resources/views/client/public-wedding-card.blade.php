<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiệp Cưới - {{ $card->groom_name ?? 'Chú Rể' }} & {{ $card->bride_name ?? 'Cô Dâu' }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

    <style>
        :root {
            --primary-rose: #e11d48;
            --accent-gold: #f59e0b;
            --dark-slate: #0f172a;
        }
        body {
            background-color: var(--dark-slate);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }
        #particle-canvas {
            position: fixed; top: 0; left: 0;
            width: 100vw; height: 100vh;
            pointer-events: none; z-index: 2;
        }
        .music-toggle-btn {
            position: fixed; bottom: 25px; right: 25px;
            z-index: 999; width: 50px; height: 50px;
            border-radius: 50%; background: var(--primary-rose);
            color: white; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 15px rgba(225, 29, 72, 0.5); cursor: pointer; border: 2px solid white;
        }
        .music-spinning { animation: spin 4s linear infinite; }
        /* Style Nút Voice Lời Mời Floating */
        .voice-toggle-btn {
            position: fixed; bottom: 85px; right: 25px;
            z-index: 999; width: 50px; height: 50px;
            border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #e11d48);
            color: white; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.5); cursor: pointer; border: 2px solid white;
            transition: transform 0.2s ease;
        }
        .voice-toggle-btn:hover { transform: scale(1.1); }
        .voice-playing { animation: voicePulse 1.5s infinite; }
        @keyframes voicePulse {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
    @stack('styles')
</head>
<body>

<canvas id="particle-canvas"></canvas>

@php
    // Nhạc nền
    $bgMusic = !empty($card->bg_music) 
        ? asset('storage/' . ltrim(str_replace('storage/', '', $card->bg_music), '/')) 
        : null;

    // Voice lời mời
    $voiceInvite = !empty($card->voice_invite) 
        ? asset('storage/' . ltrim(str_replace('storage/', '', $card->voice_invite), '/')) 
        : null;
@endphp

{{-- KHỐI NHẠC NỀN (Chỉ hiện khi thiệp có upload nhạc) --}}
@if($bgMusic)
    <div class="music-toggle-btn music-spinning" id="music-control-btn" title="Bật/Tắt Nhạc">
        <i class="bi bi-disc fs-4"></i>
    </div>
    <audio id="bg-audio" src="{{ $bgMusic }}" loop></audio>
@endif

{{-- KHỐI VOICE LỜI MỜI (Chỉ hiện khi thiệp VIP và có Upload Voice) --}}
@if($voiceInvite)
    <div class="voice-toggle-btn" id="voice-control-btn" onclick="toggleVoiceInvite()" title="Phát Voice Lời Mời" 
         style="position: fixed; bottom: 85px; right: 25px; z-index: 99999; width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #e11d48); color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.4);">
        <i class="bi bi-mic-fill fs-5" id="voiceIcon"></i>
    </div>
    <audio id="voice-audio" src="{{ $voiceInvite }}"></audio>
@endif
{{-- NƠI GIAO DIỆN CỦA MẪU 1, MẪU 2, MẪU 3 SẼ ĐƯỢC HIỂN THỊ --}}
@yield('content')

<script>
    // 1. Hiệu Ứng Hạt Kim Tuyến Chung
    const canvas = document.getElementById('particle-canvas');
    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;

    window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    });

    class Particle {
        constructor() { this.reset(); }
        reset() {
            this.x = Math.random() * width;
            this.y = height + Math.random() * 50;
            this.size = Math.random() * 3 + 1;
            this.speedY = Math.random() * 1.2 + 0.3;
            this.speedX = Math.sin(Math.random() * Math.PI) * 0.5;
            this.opacity = Math.random() * 0.7 + 0.3;
            this.color = Math.random() > 0.4 ? '#fbbf24' : '#f43f5e';
        }
        update() {
            this.y -= this.speedY;
            this.x += this.speedX;
            if (this.y < -10) this.reset();
        }
        draw() {
            ctx.save();
            ctx.globalAlpha = this.opacity;
            ctx.fillStyle = this.color;
            ctx.shadowBlur = 8;
            ctx.shadowColor = this.color;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
        }
    }

    const particles = Array.from({ length: 45 }, () => new Particle());
    function animateParticles() {
        ctx.clearRect(0, 0, width, height);
        particles.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(animateParticles);
    }
    animateParticles();

    // 2. Control Nhạc Nền Chung
    // 2. Control Nhạc Nền + Tự động phát khi khách chạm màn hình
    const musicBtn = document.getElementById('music-control-btn');
    const bgAudio = document.getElementById('bg-audio');

    if (bgAudio) {
        // Tự động phát ngay khi khách chạm/click lần đầu tiên vào bất kỳ đâu trên thiệp
        const playOnFirstTouch = () => {
            bgAudio.play().then(() => {
                if (musicBtn) musicBtn.classList.add('music-spinning');
            }).catch(() => {});
            
            // Chạy 1 lần xong gỡ sự kiện đi
            document.removeEventListener('click', playOnFirstTouch);
            document.removeEventListener('touchstart', playOnFirstTouch);
        };

        document.addEventListener('click', playOnFirstTouch);
        document.addEventListener('touchstart', playOnFirstTouch);

        if (musicBtn) {
            musicBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (!bgAudio.paused) {
                    bgAudio.pause();
                    musicBtn.classList.remove('music-spinning');
                } else {
                    // Tắt Voice nếu đang phát
                    const voiceAudio = document.getElementById('voice-audio');
                    const voiceBtn = document.getElementById('voice-control-btn');
                    const voiceIcon = document.getElementById('voiceIcon');
                    if (voiceAudio && !voiceAudio.paused) {
                        voiceAudio.pause();
                        if (voiceBtn) voiceBtn.classList.remove('voice-playing');
                        if (voiceIcon) voiceIcon.className = 'bi bi-mic-fill fs-5';
                    }

                    bgAudio.play();
                    musicBtn.classList.add('music-spinning');
                }
            });
        }
        // Nút Bật/Tắt tròn ở góc màn hình
        if (musicBtn) {
            musicBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Tránh dính sự kiện click toàn trang
                if (!bgAudio.paused) {
                    bgAudio.pause();
                    musicBtn.classList.remove('music-spinning');
                } else {
                    bgAudio.play();
                    musicBtn.classList.add('music-spinning');
                }
            });
        }
    }
     window.WEDDING_DATE = "{{ $card->wedding_date ?? '2026-12-12' }}";
    window.IS_DEMO = {{ !empty($isDemo) ? 'true' : 'false' }};
    window.BUILDER_URL = "{{ route('card.builder', $card->id ?? 1) }}";

    // Control Voice Lời Mời Floating
    function toggleVoiceInvite() {
    const voiceAudio = document.getElementById('voice-audio');
    const voiceBtn = document.getElementById('voice-control-btn');
    const voiceIcon = document.getElementById('voiceIcon');
    const bgAudio = document.getElementById('bg-audio');
    const musicBtn = document.getElementById('music-control-btn');

    if (!voiceAudio) return;

    if (!voiceAudio.paused) {
        // Tắt Voice
        voiceAudio.pause();
        if (voiceBtn) voiceBtn.classList.remove('voice-playing');
        if (voiceIcon) voiceIcon.className = 'bi bi-mic-fill fs-5';
    } else {
        // Nếu nhạc nền đang chạy -> Tắt nhạc nền trước
        if (bgAudio && !bgAudio.paused) {
            bgAudio.pause();
            if (musicBtn) musicBtn.classList.remove('music-spinning');
        }

        // Bật Voice
        voiceAudio.play().then(() => {
            if (voiceBtn) voiceBtn.classList.add('voice-playing');
            if (voiceIcon) voiceIcon.className = 'bi bi-pause-fill fs-4';
        }).catch(err => console.log('Chặn autoplay:', err));
    }
}

// Tự động chuyển icon về Micro khi nghe hết Voice
document.addEventListener('DOMContentLoaded', () => {
    const voiceAudio = document.getElementById('voice-audio');
    if (voiceAudio) {
        voiceAudio.addEventListener('ended', () => {
            const voiceBtn = document.getElementById('voice-control-btn');
            const voiceIcon = document.getElementById('voiceIcon');
            if (voiceBtn) voiceBtn.classList.remove('voice-playing');
            if (voiceIcon) voiceIcon.className = 'bi bi-mic-fill fs-5';
        });
    }
});
  // cây but chỉnh sửa sẽ chỉ hiển thị khi ở chế độ editor, không hiển thị cho khách xem thiệp
    // AUTOMATIC STICKY BAR FOR LIVE DEMO MODE
    @if(!empty($isDemo))
    if (window.self === window.top) {
        document.addEventListener("DOMContentLoaded", function () {
            const stickyBarHTML = `
                <div class="demo-sticky-bar" style="position: fixed; bottom: 0; left: 0; width: 100%; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(12px); border-top: 1px solid rgba(245, 158, 11, 0.3); padding: 12px 20px; z-index: 999999; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.5);">
                    <div class="text-white small">
                        <span class="text-white-50 d-none d-sm-inline">Đang xem demo:</span>
                        <strong style="color: var(--accent-gold, #f59e0b);" class="ms-1">Mẫu Thiệp Luxury Gold</strong>
                    </div>
                    <a href="{{ route('card.builder', $card->id ?? 1) }}" 
                       class="btn btn-sm btn-danger fw-bold rounded-pill px-3 py-2 text-white text-decoration-none shadow-sm"
                       style="font-size: 0.8rem; background: linear-gradient(135deg, #f43f5e, #e11d48); border: none;">
                        <i class="bi bi-magic me-1"></i> Dùng mẫu này ngay
                    </a>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', stickyBarHTML);
        });
    }
    @endif
   
document.getElementById('btnSearchSeat')?.addEventListener('click', function(e) {
    if (e) e.preventDefault();

    // 1. Lấy ô input và thẻ hiển thị kết quả
    let inputEl = document.getElementById('seatNameInput') || document.getElementById('guestNameInput') || this.closest('.input-group')?.querySelector('input');
    let resultDiv = document.getElementById('seatResultArea');

    if (!inputEl) return;

    let name = inputEl.value.trim();
    let cardId = "{{ $card->id ?? '' }}";

    if (!name) {
        if (resultDiv) resultDiv.innerHTML = '<div class="alert alert-warning py-2 mt-2 text-dark small">Vui lòng nhập tên!</div>';
        return;
    }

    // 2. Hiện trạng thái đang tìm
    if (resultDiv) {
        resultDiv.innerHTML = '<div class="text-warning small my-2"><span class="spinner-border spinner-border-sm me-1"></span> Đang tra cứu...</div>';
    }

    // 3. Gọi API lấy dữ liệu bàn tiệc
    fetch(`/search-table?card_id=${cardId}&keyword=${encodeURIComponent(name)}`)
        .then(res => res.json())
        .then(data => {
            if (!resultDiv) return;

            if (data.success && data.guests && data.guests.length > 0) {
                let html = '';
                data.guests.forEach(guest => {
                    html += `
                        <div class="p-3 rounded my-2 text-center" style="background: rgba(255, 255, 255, 0.15); border: 1px solid #f59e0b;">
                            <div class="fw-bold fs-4 text-warning">${guest.table_name}</div>
                            <div class="text-white small">Khách mời: <strong>${guest.guest_name}</strong></div>
                            ${guest.plus_ones > 0 ? `<div class="text-info small">+${guest.plus_ones} người đi cùng</div>` : ''}
                        </div>
                    `;
                });
                resultDiv.innerHTML = html;
            } else {
                resultDiv.innerHTML = `<div class="alert alert-info py-2 my-2 text-dark small">${data.message || 'Không tìm thấy thông tin bàn tiệc!'}</div>`;
            }
        })
        .catch(err => {
            console.error('Lỗi tra cứu:', err);
            if (resultDiv) {
                resultDiv.innerHTML = '<div class="alert alert-danger py-2 my-2 text-dark small">Lỗi kết nối máy chủ!</div>';
            }
        });
});
function findSeat(e) {
    if (e) e.preventDefault();

    // Lấy ô input: Ưu tiên tìm theo ID, nếu null thì tìm ô input nằm chung block với nút Tra cứu
    let btn = e ? e.currentTarget || e.target : null;
    let nameInput = document.getElementById('seatNameInput');
    
    if (!nameInput && btn) {
        nameInput = btn.closest('.input-group')?.querySelector('input');
    }

    let resultDiv = document.getElementById('seatResultArea');

    // Kiểm tra an toàn để tránh sập JS
    if (!nameInput) {
        console.error("Không tìm thấy thẻ input tra cứu!");
        return;
    }

    let inputValue = nameInput.value.trim();

    if (!inputValue) {
        if (resultDiv) {
            resultDiv.innerHTML = '<div class="alert alert-warning py-2 mb-0 text-dark small">Vui lòng nhập tên của bạn!</div>';
        }
        return;
    }

    if (resultDiv) {
        resultDiv.innerHTML = '<div class="text-warning small my-2"><span class="spinner-border spinner-border-sm me-1"></span> Đang tra cứu...</div>';
    }

    // Lấy card_id từ biến global hoặc từ window
    let cardId = window.cardId || "{{ $card->id ?? '' }}";

    fetch(`/search-table?card_id=${cardId}&keyword=${encodeURIComponent(inputValue)}`)
        .then(res => {
            if (!res.ok) throw new Error('HTTP status ' + res.status);
            return res.json();
        })
        .then(data => {
            if (!resultDiv) return;

            if (data.success) {
                let html = '';
                data.guests.forEach(guest => {
                    html += `
                        <div class="p-3 rounded my-2 text-center" style="background: rgba(255, 255, 255, 0.15); border: 1px solid #f59e0b;">
                            <div class="fw-bold fs-4 text-warning">${guest.table_name}</div>
                            <div class="text-white small">Khách mời: <strong>${guest.guest_name}</strong></div>
                            ${guest.plus_ones > 0 ? `<div class="text-info small">+${guest.plus_ones} người đi cùng</div>` : ''}
                            ${guest.note ? `<div class="text-white-50 small fst-italic mt-1">${guest.note}</div>` : ''}
                        </div>
                    `;
                });
                resultDiv.innerHTML = html;
            } else {
                resultDiv.innerHTML = `<div class="alert alert-info py-2 my-2 text-dark small">${data.message}</div>`;
            }
        })
        .catch(err => {
            console.error('Lỗi API:', err);
            if (resultDiv) {
                resultDiv.innerHTML = '<div class="alert alert-danger py-2 my-2 text-dark small">Lỗi kết nối tra cứu! (Kiểm tra Console F12)</div>';
            }
        });
}

// Hàm bắt sự kiện click mở Google Maps chuẩn 100%
function openGoogleMapDirect() {
    let mapUrl = window.currentMapUrl;
    
    // Nếu chưa có link map, tự lấy tên địa điểm đang hiển thị để tìm
    if (!mapUrl || mapUrl === '#' || mapUrl.trim() === '') {
        const locText = document.querySelector('[data-field="wedding_location"]')?.innerText || '';
        if (locText.trim()) {
            const cleanLoc = locText.split(',').slice(0, 3).join(',');
            mapUrl = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(cleanLoc); 
        } 
    } 
 
    if (mapUrl && mapUrl !== '#') { 
        window.open(mapUrl, '_blank'); 
    } else { 
        alert('Vui lòng nhập địa điểm lễ cưới ở bảng bên trái!'); 
    } 
} 
 
// Cập nhật biến currentMapUrl mỗi khi bên Builder gửi dữ liệu sang 
window.addEventListener('message', function (e) { 
    if (!e.data || e.data.type !== 'UPDATE_CARD_FIELD') return; 
 
    if (e.data.field === 'map_link') { 
        window.currentMapUrl = e.data.value; 
    } 
    // Cập nhật Voice lời mời từ Builder (Live Preview)
        if (event.data.type === 'UPDATE_CARD_FIELD' && event.data.field === 'voice_invite') {
            let voiceAudioEl = document.getElementById('voice-audio');
            let voiceBtn = document.getElementById('voice-control-btn');

            if (!voiceAudioEl) {
                voiceAudioEl = document.createElement('audio');
                voiceAudioEl.id = 'voice-audio';
                document.body.appendChild(voiceAudioEl);
            }

            voiceAudioEl.src = event.data.value;
            
            if (!voiceBtn) {
                const btnHtml = `
                    <div class="voice-toggle-btn" id="voice-control-btn" onclick="toggleVoiceInvite()" title="Phát/Tạm dừng Voice Lời Mời">
                        <i class="bi bi-mic-fill fs-5" id="voiceIcon"></i>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', btnHtml);
            }
        }
});
document.addEventListener('DOMContentLoaded', function() {
    let mediaRecorder = null;
    let audioChunks = [];
    let recordedAudioBlob = null;

    const btnRecord = document.getElementById('btnRecord') || document.querySelector('.btn-voice-wish');
    const audioPreview = document.getElementById('audioPreview');
    const wishForm = document.getElementById('wishForm') || document.querySelector('form[action*="voice-wish"]');

    // 1. Xử lý ghi âm Voice
    if (btnRecord) {
        btnRecord.addEventListener('click', async () => {
            if (!mediaRecorder || mediaRecorder.state === "inactive") {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];

                    mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                    
                    mediaRecorder.onstop = () => {
                        recordedAudioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                        if (audioPreview) {
                            audioPreview.src = URL.createObjectURL(recordedAudioBlob);
                            audioPreview.classList.remove('d-none');
                        }
                        btnRecord.innerHTML = '🔄 Thu âm lại';
                    };

                    mediaRecorder.start();
                    btnRecord.innerHTML = '⏹️ Dừng thu âm';
                } catch (err) {
                    alert("Vui lòng cấp quyền Microphone trên trình duyệt!");
                }
            } else if (mediaRecorder.state === "recording") {
                mediaRecorder.stop();
            }
        });
    }

    // 2. Xử lý Gửi Form Lời Chúc & Voice
    if (wishForm) {
        wishForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Tự động tìm ô input Tên & Lời chúc bất kể ID là gì
            const nameInput = document.getElementById('wish_name') || wishForm.querySelector('input[name="name"]') || wishForm.querySelector('input[type="text"]');
            const noteInput = document.getElementById('wish_text') || wishForm.querySelector('textarea[name="note"]') || wishForm.querySelector('textarea');
            const fileInput = document.getElementById('wish_voice_file') || wishForm.querySelector('input[type="file"]');

            const name = nameInput ? nameInput.value.trim() : '';
            const note = noteInput ? noteInput.value.trim() : '';

            if (!name) {
                alert("Vui lòng nhập tên của bạn!");
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('name', name);
            formData.append('note', note || '[Lời chúc bằng giọng nói]');

            // Ưu tiên 1: File ghi âm trực tiếp -> Ưu tiên 2: File chọn từ máy
            if (recordedAudioBlob) {
                formData.append('audio', recordedAudioBlob, 'voice_wish.webm');
            } else if (fileInput && fileInput.files && fileInput.files.length > 0) {
                formData.append('audio', fileInput.files[0]);
            }

            const routeUrl = "{{ route('wedding.voiceWish', $card->slug ?? 'sample') }}";

            try {
                const response = await fetch(routeUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const resData = await response.json();
                if (response.ok && resData.success) {
                    alert("Gửi lời chúc & Voice thành công! ❤️");
                    location.reload();
                } else {
                    alert(resData.message || "Gửi thất bại!");
                }
            } catch (err) {
                alert("Lỗi kết nối máy chủ!");
            }
        });
    }
});
// =========================================================================
    // 3. LẮNG NGHE LỆNH TỪ TRANG BUILDER ĐỂ PHÁT NHẠC NỀN XEM THỬ (LIVE PREVIEW)
    // =========================================================================
    window.addEventListener('message', function (event) {
        if (!event.data) return;

        // Bắt sự kiện khi bên trang Builder đẩy nhạc sang
        if (event.data.type === 'UPDATE_CARD_FIELD' && event.data.field === 'bg_music') {
            let audioEl = document.getElementById('bg-audio');

            // Nếu mẫu thiệp chưa có sẵn thẻ <audio> thì tự tạo mới
            if (!audioEl) {
                audioEl = document.createElement('audio');
                audioEl.id = 'bg-audio';
                audioEl.loop = true;
                document.body.appendChild(audioEl);
            }

            // Gán đường dẫn file nhạc mới vừa chọn từ Builder
            audioEl.src = event.data.value;

            // Phát nhạc
            audioEl.play().then(() => {
                const musicBtn = document.getElementById('music-control-btn');
                if (musicBtn) musicBtn.classList.add('music-spinning');
            }).catch(err => {
                console.log("Trình duyệt chặn Autoplay nhạc. Bấm vào Preview để nghe thử!");
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/wedding-builder.js') }}"></script>

@stack('scripts')
</body>
</html>