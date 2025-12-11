<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip untuk request AJAX dan route selain landing page
        if ($request->ajax() || !$request->is('/')) {
            return $next($request);
        }

        $response = $next($request);

        // Cek jika belum ada session popup dan belum ada cookie
        if (!$request->session()->has('welcome_popup_shown') && 
            !$request->cookie('welcome_popup_disabled')) {
            
            $request->session()->put('welcome_popup_shown', true);
            
            // Inject popup HTML dan script
            $content = $response->getContent();
            
            $popupHTML = $this->getPopupHTML();
            $popupScript = $this->getPopupScript();
            
            // Sisipkan sebelum </body>
            $content = str_replace('</body>', $popupHTML . $popupScript . '</body>', $content);
            $response->setContent($content);
        }

        return $response;
    }

    private function getPopupHTML(): string
    {
        return 
        <!-- Welcome Popup Modal -->
        <div class="modal fade" id="welcomeModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content welcome-popup">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center pt-0">
                        <div class="welcome-icon mb-3">
                            <i class="fas fa-university fa-4x" style="color: var(--primary-brown);"></i>
                        </div>
                        <h2 class="fw-bold mb-3" style="color: var(--primary-brown);">Selamat Datang! 🎉</h2>
                        <p class="lead mb-4" style="color: var(--dark-brown);">
                            Selamat datang di <strong>Q-TRACK SPMI Digital</strong>
                        </p>
                        <p class="mb-4" style="color: var(--dark-brown);">
                            Sistem Penjaminan Mutu Internal Digital untuk Transformasi Pendidikan yang Lebih Baik
                        </p>
                        
                        <div class="features-list row text-start mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle me-2" style="color: var(--primary-brown);"></i>
                                    <span style="color: var(--dark-brown);">Kelola 12 Standar Mutu</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle me-2" style="color: var(--primary-brown);"></i>
                                    <span style="color: var(--dark-brown);">Audit Digital Terintegrasi</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle me-2" style="color: var(--primary-brown);"></i>
                                    <span style="color: var(--dark-brown);">Monitoring Real-time</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle me-2" style="color: var(--primary-brown);"></i>
                                    <span style="color: var(--dark-brown);">6 Program Studi</span>
                                </div>
                            </div>
                        </div>

                        <div class="promo-banner rounded p-3 mb-4" style="background: var(--light-brown); border-left: 4px solid var(--primary-brown);">
                            <h6 class="fw-bold mb-1" style="color: var(--primary-brown);">
                                <i class="fas fa-rocket me-2"></i>TRANSFORMASI DIGITAL SPMI
                            </h6>
                            <p class="mb-0 small" style="color: var(--dark-brown);">
                                "Kelola Mutu Pendidikan Lebih Efisien & Efektif"
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer border-0 flex-column">
                        <div class="w-100">
                            <button class="btn btn-lg w-100 mb-3" onclick="startExploring()" 
                                    style="background: linear-gradient(135deg, var(--secondary-brown), var(--primary-brown)); color: white; border: none; border-radius: 8px; padding: 12px;">
                                <i class="fas fa-play-circle me-2"></i>Mulai Jelajahi Sekarang
                            </button>
                            
                            <div class="form-check text-start">
                                <input class="form-check-input" type="checkbox" id="dontShowAgain">
                                <label class="form-check-label" for="dontShowAgain" style="color: var(--dark-brown);">
                                    Jangan tampilkan pesan ini lagi
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .welcome-popup {
                border-radius: 20px;
                border: none;
                box-shadow: 0 20px 60px rgba(153, 102, 0, 0.3);
                overflow: hidden;
            }

            .welcome-popup .modal-content {
                border-radius: 20px;
                border: 2px solid var(--light-brown);
            }

            .welcome-popup .modal-header {
                position: absolute;
                top: 15px;
                right: 15px;
                z-index: 1;
                border: none;
            }

            .welcome-popup .modal-body {
                padding: 2rem;
            }

            .welcome-icon {
                animation: bounce 2s infinite;
            }

            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {
                    transform: translateY(0);
                }
                40% {
                    transform: translateY(-10px);
                }
                60% {
                    transform: translateY(-5px);
                }
            }

            .features-list {
                background: rgba(153, 102, 0, 0.05);
                border-radius: 10px;
                padding: 1.5rem;
            }

            .promo-banner {
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(153, 102, 0, 0.4);
                }
                70% {
                    box-shadow: 0 0 0 10px rgba(153, 102, 0, 0);
                }
                100% {
                    box-shadow: 0 0 0 0 rgba(153, 102, 0, 0);
                }
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(153, 102, 0, 0.3);
            }

            /* Modal backdrop */
            .modal-backdrop.show {
                opacity: 0.8;
            }
        </style>
        ;
    }

    private function getPopupScript(): string
    {
        return 
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tampilkan modal setelah halaman fully loaded
                setTimeout(function() {
                    const welcomeModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
                    welcomeModal.show();
                }, 1500);

                // Handle 'Jangan tampilkan lagi'
                document.getElementById('dontShowAgain')?.addEventListener('change', function() {
                    if (this.checked) {
                        setPopupPreference();
                    }
                });

                // Close modal when clicking outside
                document.getElementById('welcomeModal')?.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closePopup();
                    }
                });
            });

            function startExploring() {
                closePopup();
                // Scroll ke hero section
                document.getElementById('home')?.scrollIntoView({ behavior: 'smooth' });
            }

            function closePopup() {
                const modal = bootstrap.Modal.getInstance(document.getElementById('welcomeModal'));
                modal.hide();
            }

            function setPopupPreference() {
                // Set cookie untuk 30 hari
                const date = new Date();
                date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
                document.cookie = 'welcome_popup_disabled=true; expires=' + date.toUTCString() + '; path=/';
                
                // Kirim preference ke backend
                fetch('/api/popup-preference', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
                    },
                    body: JSON.stringify({ dont_show_again: true })
                }).catch(error => console.log('Preference saved locally'));
            }
        </script>
        ;
    }
}