<div class="col-12 mb-3 mb-md-0 d-flex flex-column flex-md-row align-items-center justify-content-center text-center">
    <!-- Course Information Section -->
    <div class="order-0 col-lg-8 col-md-8 col-sm-12 col-12 mb-3 mb-md-0">
{{--        <a href="{{ route('frt.crs.show', $course->slug) }}">--}}
{{--            <h3 class="text-white mb-3">{{ strtoupper($course->title) }}</h3>--}}
{{--        </a>--}}

        <div class="mt-4 d-flex flex-column align-items-center justify-content-center text-center">
            <img src="https://admin.nanolympiad.org/logos/2025_03_04_111021_logo.png" alt="Logo" class="course-logo mb-3">

            <a href="javascript:void(0);" class="btn btn-outline-warning text-white btn-sm d-flex align-items-center" id="reportBugBtn">
                <i class="icofont-bug me-1"></i> Report Bug
            </a>

            <div class="mt-2 text-muted" style="font-size: 0.9rem;">
                <small>&copy; {{ date('Y') }} All Rights Reserved
                    <a class="footer-link" href="{{ env('APP_URL') }}" style="color:white;">
                        {{ env('APP_NAME') }}
                    </a>
                </small>
            </div>
        </div>


        <style>
            .footer-link:hover{
                text-decoration: underline;
            }
            @keyframes floatFade {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .course-logo {
                max-width: 200px;
                max-height: 100px;
                animation: floatFade 1s ease-out forwards;
            }

            .course-logo:hover {
                transform: scale(1.05);
            }
            @keyframes subtlePulse {
                0%   { transform: scale(1); }
                50%  { transform: scale(1.03); }
                100% { transform: scale(1); }
            }

            .course-logo {
                max-width: 200px;
                max-height: 100px;
                animation: subtlePulse 3s ease-in-out infinite;
            }

        </style>
    </div>

    <!-- Course Body Section -->
{{--    @if(!is_null($course->body))--}}
{{--        <div class="order-0 col-lg-4 col-md-4 col-sm-12 col-12 mb-3 mb-md-0">--}}
{{--            <div class="course-body text-light" style="background-color: rgba(0, 0, 0, 0.7); padding: 20px; border-radius: 8px;">--}}
{{--                <p class="lead">{!! $course->body !!}</p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
</div>

<!-- Report Bug Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('reportBugBtn').addEventListener('click', function() {
        const pathParts = window.location.pathname.split('/');
        const episodeSlug = pathParts[pathParts.length - 1];
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        Swal.fire({
            title: 'Report a Bug',
            input: 'textarea',
            inputLabel: 'Please describe the bug you encountered',
            inputPlaceholder: 'Type your bug report here...',
            inputAttributes: {
                'aria-label': 'Type your bug report here'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: async (report) => {
                if (!report || report.trim().length === 0) {
                    Swal.showValidationMessage('Please describe the bug');
                    return false;
                }

                try {
                    const response = await fetch(`/courses/episodes/${episodeSlug}/report-bug`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ report: report.trim() })
                    });

                    // Check content type
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        throw new Error(text || 'Invalid response from server');
                    }

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Request failed');
                    }

                    return data;
                } catch (error) {
                    let errorMsg = error.message;
                    // Handle common HTML responses
                    if (errorMsg.includes('<html') || errorMsg.includes('<!DOCTYPE')) {
                        errorMsg = 'Please login to submit a bug report';
                    }
                    Swal.showValidationMessage(errorMsg);
                    return false;
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.value.success || 'Thank you for your bug report!'
                    });
                }
            }
        });
    });
    </script>

    <style>
        .course-logo {
        max-width: 200px;
        max-height: 100px;
        margin-right: 10px;
    }

        /* Optional: Adding responsiveness for the course body */
        .course-body {
        background-color: rgba(0, 0, 0, 0.7);
        padding: 20px;
        border-radius: 8px;
    }
    </style>
