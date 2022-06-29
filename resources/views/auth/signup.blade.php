<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    {{--    <base href="{{URL::to('/')}}/">--}}
    <title>Create link</title>
    <meta charset="utf-8"/>
    <meta name="description"
          content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free."/>
    <meta name="keywords"
          content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title"
          content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme"/>

    <link rel="shortcut icon" href="{{URL::to('/')}}/assets/media/logos/ap_logos-04.svg"/>
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{URL::to('/')}}/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="{{URL::to('/')}}/assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="bg-body">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Sign-up -->
    <div
        class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
        style="background-image: url(assets/media/illustrations/sketchy-1/14.png">
        <!--begin::Content-->
        <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">

            <!--begin::Wrapper-->
            <div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                <!--begin::Form-->
                <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form">
                @csrf
                <!--begin::Heading-->
                    <div class="mb-10 text-center">
                        <!--begin::Title-->
                        <h1 class="text-dark mb-3">Create link</h1>
                        <!--end::Title-->
                        <!--begin::Link-->
                        <div class="text-gray-400 fw-bold fs-4">Already have an account?
                            <a href="{{route('login')}}" class="link-primary fw-bolder">Sign in here</a></div>
                        <!--end::Link-->
                    </div>
                    <!--end::Heading-->
                    <!--begin::Action-->
                    <button type="button" class="btn btn-light-primary fw-bolder w-100 mb-10 d-none">
                        <img alt="Logo" src="{{URL::to('/')}}/assets/media/svg/brand-logos/google-icon.svg"
                             class="h-20px me-3"/>Sign in with Google
                    </button>
                    <!--end::Action-->
                    <!--begin::Separator-->
                    <div class="d-flex align-items-center mb-10 d-none">
                        <div class="border-bottom border-gray-300 mw-50 w-100"></div>
                        <span class="fw-bold text-gray-400 fs-7 mx-2">OR</span>
                        <div class="border-bottom border-gray-300 mw-50 w-100"></div>
                    </div>
                    <!--end::Separator-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bolder text-dark fs-6 required">Username</label>
                        <input class="form-control form-control-lg form-control-solid" type="text" placeholder=""
                               name="name" id="name" autocomplete="off"/>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="form-label fw-bolder text-dark fs-6 required">Phone</label>
                        <input class="form-control form-control-lg form-control-solid" type="text" placeholder=""
                               name="phone" id="phone" autocomplete="off"/>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="form-label fw-bolder text-dark fs-6 required">Number</label>
                        <input class="form-control form-control-lg form-control-solid" type="text" placeholder=""
                               name="number" id="number" autocomplete="off"/>
                    </div>


                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="button" id="kt_sign_up_submit" class="btn btn-lg btn-warning">
                            <span class="indicator-label">Register</span>
                            <span class="indicator-progress">Loading...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Content-->
        <!--begin::Footer-->
        <div class="d-flex flex-center flex-column-auto p-10 d-none">
            <!--begin::Links-->
            <div class="d-flex align-items-center fw-bold fs-6">
                <a href="javascript:;" class="text-muted text-hover-primary px-2">About</a>
                <a href="javascript:;" class="text-muted text-hover-primary px-2">Contact</a>
                <a href="javascript:;" class="text-muted text-hover-primary px-2">Contact Us</a>
            </div>
            <!--end::Links-->
        </div>
        <!--end::Footer-->
    </div>
    <!--end::Authentication - Sign-up-->
</div>
<!--end::Main-->
{{--<script>var hostUrl = "assets/";</script>--}}
<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(used by all pages)-->
<script src="{{URL::to('/')}}/assets/plugins/global/plugins.bundle.js"></script>
<script src="{{URL::to('/')}}/assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Page Custom Javascript(used by this page)-->
<script>
    $(function () {
        $('#kt_sign_up_submit').on('click', function (e) {
            let btn = $(this);
            btn.attr("data-kt-indicator", "on").prop('disabled', true);

            e.preventDefault();

            var data = {
                name: $('#name').val(),
                phone: $('#phone').val(),
                number: $('#number').val(),
                _token: $('input[name=_token]').val(),
            };

            $.ajax({
                url: '{{URL::to('/')}}/signup',
                method: 'post',
                data: data,
                success: function (res, status, xhr, $form) {

                    debugger
                    setTimeout(function () {
                        if (res.error) {
                            btn.removeAttr('data-kt-indicator').prop('disabled', false);
                            toastr.error(res.error);
                        } else {
                            location.href = "{{URL::to('/')}}/link/" + res.link.link;
                        }
                    }, 300);
                },
                error: function (error) {
                    btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    toastr.error('server error');
                }
            });
        });
    })
</script>
<!--end::Page Custom Javascript-->
<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
