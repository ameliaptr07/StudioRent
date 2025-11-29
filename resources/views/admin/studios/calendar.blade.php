@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Kalender Ketersediaan Studio</h1>
    <div id="calendar"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($studioAvailability),  // Menampilkan data ketersediaan studio
        });
        calendar.render();
    });
</script>
@endsection
