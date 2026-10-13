{{-- Bikram Sambat date picker: month lengths from App\Support\NepaliDate, so PHP and JS share one table. --}}
<script>window.NEPALI_CALENDAR = @json(\App\Support\NepaliDate::table());</script>
<script src="{{ asset('backend/js/nepali-date.js') }}?v={{ filemtime(public_path('backend/js/nepali-date.js')) }}"></script>
