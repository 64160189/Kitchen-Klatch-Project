@extends('layouts.app')

@section('content')
    <!-- Link to the CSS file -->
    <script>
        mix.css('resources/css/app.css', 'public/css');
    </script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="container">
        <div class="row">
            {{-- SIDEBAR --}}
            <div class="sidebar col-2">
                {{-- Appear when window width <= 300 --}}
                <nav class="navbar fixed-bottom ms-2">
                    <button class="navbar-toggler bg-warning" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                        aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title fw-bold" id="offcanvasNavbarLabel">Admin Sidebar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <li class="nav-item">
                                    <a class="nav-link active fw-bold" aria-current="page">หน้าหลัก</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/admin/home">แดชขอร์ด</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active fw-bold mt-4" aria-current="page">ตารางข้อมูล</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/admin/table/user">บัญชีผู้ใช้ทั้งหมด</a>
                                    <a class="nav-link" href="/admin/table/post">โพสต์ทั้งหมด</a>
                                    <a class="nav-link" href="{{ route('admin.viewReportedPosts') }}">โพสต์ที่ถูกรายงานทั้งหมด</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                {{-- Appear when window width > 300 --}}
                <div class="sidebar-normal card">
                    <h5 class="fw-bold card-header" aria-current="page">หน้าหลัก</h5>
                    <div class="card-body">
                        <a class="nav-link" href="/admin/home">แดชขอร์ด</a>
                    </div>

                    <h5 class="fw-bold card-header" aria-current="page">ตารางข้อมูล</h5>
                    <div class="card-body">
                        <a class="nav-link" href="/admin/table/user">บัญชีผู้ใช้ทั้งหมด</a>
                        <a class="nav-link mt-2" href="/admin/table/post">โพสต์ทั้งหมด</a>
                        <a class="nav-link mt-2" href="{{ route('admin.viewReportedPosts') }}">โพสต์ที่ถูกรายงานทั้งหมด</a>
                    </div>
                </div>
            </div>

            <div class="col row justify-content-center">
                <div class="col">
                    <div class="row d-flex justify-content-center">

                        <div class="col-3 card me-3 p-0 border">
                            <div class="card-header" style="background: #2dc4ff">
                                <span class="fw-semibold">
                                    <span class="">จำนวนบัญชีผู้ใช้ทั้งหมด</span></br>
                                    <span class="fs-1 fw-bold" style="color: white">{{ $AllUsers }} </span>
                                    <span>บัญชี</span>
                                </span>
                            </div>
                            <div class="card-body mt-1 fw-semibold" style="background: #2dc4ff">
                                วันนี้ <span class="fw-bold" style="color: white;"> {{ $todayUsers }} </span> บัญชี
                            </div>
                            <div class="card-body mt-1 fw-semibold" style="background: #58d0ff">
                                7 วัน <span class="fw-bold" style="color: white"> {{ $last7dayUsers }} </span> บัญชี
                            </div>
                            <div class="card-footer mt-1 fw-semibold" style="background: #93ddfa">
                                30 วัน <span class="fw-bold" style="color: white"> {{ $last30dayUsers }} </span> บัญชี
                            </div>
                        </div>

                        <div class="col-3 card me-3 p-0 border">
                            <div class="card-header" style="background: #ffa806">
                                <span class="fw-semibold">
                                    <span>จำนวนโพสต์ทั้งหมด</span></br>
                                    <span class="fs-1 fw-bold" style="color: white">{{ $AllPosts }} </span> โพสต์
                                </span>
                            </div>
                            <div class="card-body mt-1 fw-semibold" style="background: #ffa806">
                                วันนี้ <span class="fw-bold" style="color: white"> {{ $todayPosts }} </span> โพสต์
                            </div>
                            <div class="card-body mt-1 fw-semibold" style="background: #ffbc3e">
                                7 วัน <span class="fw-bold" style="color: white"> {{ $last7dayPosts }} </span> โพสต์
                            </div>
                            <div class="card-footer mt-1 fw-semibold" style="background: #ffd17b">
                                30 วัน <span class="fw-bold" style="color: white"> {{ $last30dayPosts }} </span> โพสต์
                            </div>
                        </div>

                        <div class="col-3 card p-0 border">
                            <div class="card-header" style="background: #F20505">
                                <span class="fw-semibold">
                                    <span>มีการรายงานทั้งหมด</span></br>
                                    <span class="fs-1 fw-bold" style="color: white">{{ $AllReports }} </span> ครั้ง
                                </span>
                            </div>
                            <div class="card-body mt-1 fw-semibold" style="background: #F20505">
                                วันนี้ <span class="fw-bold" style="color: white"> {{ $todayReports }} </span> ครั้ง
                            </div>
                            <div class="card-body mt-1 fw-semibold" style="background: #F63535">
                                7 วัน <span class="fw-bold" style="color: white"> {{ $last7dayReports }} </span> ครั้ง
                            </div>
                            <div class="card-footer mt-1 fw-semibold" style="background: #F46F6F">
                                30 วัน <span class="fw-bold" style="color: white"> {{ $last30dayReports }} </span> ครั้ง
                            </div>
                        </div>
                    </div>

                    <!-- Chart.js Graph -->
                    <div class="row d-flex justify-content-center">
                        <div class="col-9 card mt-4">
                            <div class="card-body">
                                <canvas id="userStatsChart"></canvas>
                            </div>
                        </div>

                        <div class="col-9 card mt-4">
                            <div class="card-body">
                                <canvas id="postStatsChart"></canvas>
                            </div>
                        </div>

                        <div class="col-9 card mt-4">
                            <div class="card-body">
                                <canvas id="reportStatsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- chartjs and jquery script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // new USERS chart
        $(document).ready(function() {
            // Fetch and prepare the data from the controller
            const labels = @json($Ulabels);
            const data = @json($Udata);

            console.log("Labels:", labels);
            console.log("Data:", data);

            // Initialize Chart.js only if there's data
            if (labels.length && data.length) {
                const ctx = document.getElementById('userStatsChart').getContext('2d');
                const userStatsChart = new Chart(ctx, {
                    type: 'bar', // Bar chart for user counts by day
                    data: {
                        labels: labels, // Dates on X-axis
                        datasets: [{
                            label: 'จำนวนผู้ใช้ใหม่ใน 7 วันที่ผ่านมา',
                            data: data, // Counts on Y-axis
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } else {
                console.log("No data available for chart");
            }
        });

        // new POSTS chart
        $(document).ready(function() {
            // Fetch and prepare the data from the controller
            const labels = @json($Plabels);
            const data = @json($Pdata);

            console.log("Labels:", labels);
            console.log("Data:", data);

            // Initialize Chart.js only if there's data
            if (labels.length && data.length) {
                const ctx = document.getElementById('postStatsChart').getContext('2d');
                const postStatsChart = new Chart(ctx, {
                    type: 'bar', // Bar chart for user counts by day
                    data: {
                        labels: labels, // Dates on X-axis
                        datasets: [{
                            label: 'จำนวนโพสต์ใหม่ใน 7 วันที่ผ่านมา',
                            data: data, // Counts on Y-axis
                            backgroundColor: 'rgba(242, 159, 0, 0.5)',
                            borderColor: 'rgba(242, 159, 0, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } else {
                console.log("No data available for chart");
            }
        });

        // REPORTED posts chart
        $(document).ready(function() {
            // Fetch and prepare the data from the controller
            const labels = @json($Rlabels);
            const data = @json($Rdata);

            console.log("Labels:", labels);
            console.log("Data:", data);

            // Initialize Chart.js only if there's data
            if (labels.length && data.length) {
                const ctx = document.getElementById('reportStatsChart').getContext('2d');
                const reportStatsChart = new Chart(ctx, {
                    type: 'bar', // Bar chart for user counts by day
                    data: {
                        labels: labels, // Dates on X-axis
                        datasets: [{
                            label: 'การรายงานโพสต์ใน 7 วันที่ผ่านมา',
                            data: data, // Counts on Y-axis
                            backgroundColor: 'rgba(242, 5, 0, 0.5)',
                            borderColor: 'rgba(242, 5, 0, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } else {
                console.log("No data available for chart");
            }
        });
    </script>
@endsection
