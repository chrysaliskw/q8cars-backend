<x-admin-layout title="Dashboard">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



        <div class="row">
            <div class="col-sm-12">
                <h4 class="pull-left page-title">Welcome!  </h4>
                <ol class="breadcrumb pull-right">
                    <li class="active">Dashboard</li>
                </ol>
            </div>
        </div>

        <!-- Cards Section  Start -->
        <div class="row">
            <div class="col-md-4">

                <a href="{{route('admin.car.index')}}" target="_blank">
                    <div class="dashboard-card dashboard-card-2 d-flex justify-content-around align-items-center">
                        <span class="mini-stat-icon-dashboard bg-white" ><i class="fa fa-car" style="color:thistle"></i></span>
                        <div class="data">
                        <h4 class=" text-white font-weight-bold">Cars : {{$viewData['totalCars']}}</h4>
                        <h5 class="text-white text-4  m-0">
                            Just Launch : {{$viewData['justLaunchedCars']}}
                        </h5>
                        <h5 class="text-white text-4  m-0">
                            Upcoming :{{$viewData['upcomingCars']}}
                        </h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">

            <a href="{{ route('admin.user.index') }}" target="_blank">
                <div class="dashboard-card dashboard-card-1 d-flex justify-content-around align-items-center">
                    <span class="mini-stat-icon-dashboard bg-white"><i class="ion-android-contacts" style="color: gold;"></i></span>
                    <div class="data">
                        <h4 class=" text-white font-weight-bold">Users : {{$viewData['totalUsers']}}</h4>
                        <h5 class="text-white text-4  m-0">
                            Active :  {{$viewData['activeUsers']}}
                        </h5>
                        <h5 class="text-white text-4  m-0">
                            Inactive : {{$viewData['inactiveUser']}}
                        </h5>
                    </div>
                </div>
            </a>
        </div>

            <div class="col-md-4">

                <a href="{{ route('admin.partner-banks.index') }}" target="_blank">
                    <div class="dashboard-card dashboard-card-3 d-flex justify-content-around align-items-center">
                        <span class="mini-stat-icon-dashboard bg-white"><i class="fa fa-building" style="color:yellowgreen;"></i></span>
                        <div class="data">
                            <h4 class="text-white font-weight-bold">Partner Banks :  {{$viewData['totalBanks']}}</h4>
                            <h5 class="text-white text-4  m-0">
                                Active :  {{$viewData['activeBanks']}}
                        </h5>
                        <h5 class="text-white text-4  m-0">
                            Inactive :  {{$viewData['inactiveBanks']}}
                        </h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">

            <a href="{{ route('admin.loan-requests.index') }}" target="_blank">
                <div class="dashboard-card dashboard-card-4 d-flex justify-content-around align-items-center">
                    <span class="mini-stat-icon-dashboard bg-white" ><i class="fa fa-money-bill-wave" style="color:palevioletred;"></i></span>
                    <div class="data">
                        <h4 class="text-white font-weight-bold">Loan Request :  {{$viewData['totalLoanRequests']}}</h4>
                        <h5 class="text-white text-4  m-0">
                            New : {{$viewData['newLoanRequests']}}
                        </h5>
                        <h5 class="text-white text-4  m-0">
                            Completed : {{$viewData['completedLoanRequests']}}
                        </h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">

            <a href="{{ route('admin.test-ride-requests.index') }}" target="_blank">
                <div class="dashboard-card dashboard-card-5 d-flex justify-content-around align-items-center">
                    <span class="mini-stat-icon-dashboard bg-white" ><i class="ion-ios7-paper" style="color:deepskyblue"></i></span>
                    <div class="data">
                        <h4 class="text-white font-weight-bold">TestRide Bookings : {{$viewData['totalTestRideRequest']}}</h4>
                        <h5 class="text-white text-4  m-0">
                            New : {{ $viewData['newTestRideRequest'] }}
                        </h5>
                        <h5 class="text-white text-4  m-0">
                           Completed : {{ $viewData['completedTestRideRequest'] }}
                        </h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">

            <a href="{{ route('admin.offer-requests.index') }}" target="_blank">
                <div class="dashboard-card dashboard-card-6 d-flex justify-content-around align-items-center">
                <span class="mini-stat-icon-dashboard bg-white" ><i class="ion-ios7-pricetags" style="color:tomato;"></i></span>
                <div class="mini-stat-info  text-dark">
                        <h4 class="text-white font-weight-bold">Offer Requests : {{$viewData['totalOfferRequests']}}</h4>
                        <h5 class="text-white text-4  m-0">
                            New : {{$viewData['newOfferRequests']}}
                        </h5>
                        <h5 class="text-white text-4  m-0">
                            Completed : {{$viewData['completedOfferRequests']}}
                        </h5>
                    </div>
                </div>
            </a>
        </div>

        </div>

        <!-- Cards Section  End -->

        <!-- Graphs Section Start -->
    {{-- <div class="row mt-4">
        <!-- Bar Chart Section -->
        <div class="col-md-7">
            <h5 class="text-center">TestRide Bookings vs Loan Applications</h5>
            <canvas id="testRideVsLoanChart"></canvas>
        </div>

        <!-- Doughnut Chart Section -->
        <div class="col-md-5">
            <div class="col-md-12">
                <h5 class="text-center">TestRide Bookings vs Loan Applications</h5>
                <x-form-select :options="$viewData['brands']" field="brand_id" id="brand_id">
                    <option value="" selected>Select a Brand</option>
                    @foreach($viewData['brands'] as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </x-form-select>
                <input type="hidden" id="brand_id_text" name="brand_id_text" />
            </div>

            <canvas id="enquiriesTestRideReviewChart"></canvas>
        </div>
    </div> --}}

    <div class="row mt-4">
        <!-- Bar Chart Section -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header text-center bg-transparent">
                    <h5>TestRide Bookings vs Loan Applications</h5>
                </div>
                <div class="card-body">
                    <canvas id="testRideVsLoanChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart Section -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center bg-transparent">
                    <h5>Offers, Test Rides and Loans</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <x-form-select :options="$viewData['brands']" field="brand_id" id="brand_id">
                            <option value="" selected>Select a Brand</option>
                            @foreach($viewData['brands'] as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </x-form-select>
                    </div>
                    <canvas id="enquiriesTestRideReviewChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphs Section End -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        var totalLoanRequests = {!! json_encode($viewData['loanRequestsCounts']) !!};
        var newLoanRequests = {!! json_encode($viewData['newLoanRequests']) !!};
        var completedLoanRequests = {!! json_encode($viewData['completedLoanRequests']) !!};

        var totalTestRideRequest = {!! json_encode($viewData['testRideRequestsCounts']) !!};
        var newTestRideRequest = {!! json_encode($viewData['newTestRideRequest']) !!};
        var completedTestRideRequest = {!! json_encode($viewData['completedTestRideRequest']) !!};
        // Bar Chart for TestRide vs Loan Application
        var ctxBar = document.getElementById('testRideVsLoanChart').getContext('2d');
        var testRideVsLoanChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],  // Example labels for months
                datasets: [{
                    label: 'Test Ride Bookings',
                    data: totalTestRideRequest,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Loan Applications',
                    data: totalLoanRequests,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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

        // // Doughnut Chart for Enquiries, Test Rides, and Reviews
        // $(document).ready(function() {
        // // Initialize the chart with default values (zeroes)
        //     var ctxDoughnut = document.getElementById('enquiriesTestRideReviewChart').getContext('2d');
        //     var enquiriesTestRideReviewChart = new Chart(ctxDoughnut, {
        //         type: 'doughnut',  // Set chart type to doughnut
        //         data: {
        //             labels: ['Offer Requests', 'Test Rides', 'Reviews'],  // Labels
        //             datasets: [{
        //                 data: [10, 30, 30],  // Initial values (will be replaced with actual data)
        //                 backgroundColor: ['#FFCE56', '#36A2EB', '#FF6384'],
        //                 borderWidth: 1
        //             }]
        //         },
        //         options: {
        //             responsive: true,
        //             plugins: {
        //                 legend: {
        //                     position: 'top',
        //                 },
        //                 tooltip: {
        //                     callbacks: {
        //                         label: function(tooltipItem) {
        //                             return tooltipItem.label + ': ' + tooltipItem.raw + '%';  // Tooltip format
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     });

        //     // Handle brand change event
        //     $('#brand_id').change(function() {
        //         var brandId = $(this).val();  // Get selected brand ID

        //         if (brandId) {
        //             // Send AJAX request to fetch data based on selected brand
        //             $.ajax({
        //                 url: '{{ route('admin.get-brand-data') }}',  // Ensure this URL matches the route
        //                 type: 'POST',
        //                 data: {
        //                     brand_id: brandId,
        //                     _token: '{{ csrf_token() }}'  // CSRF token for security
        //                 },
        //                 success: function(response) {
        //                     // Update the doughnut chart with the response data
        //                     enquiriesTestRideReviewChart.data.datasets[0].data = [
        //                         response.offerRequests,
        //                         response.testRideRequests,
        //                         response.reviews
        //                     ];
        //                     enquiriesTestRideReviewChart.update();  // Refresh chart with new data
        //                 },
        //                 error: function(error) {
        //                     console.log("Error:", error);  // Log error if request fails
        //                 }
        //             });
        //         }
        //     });
        // });

        $(document).ready(function() {
        var ctxDoughnut = document.getElementById('enquiriesTestRideReviewChart').getContext('2d');
        var enquiriesTestRideReviewChart = new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['Offers', 'Test Rides', 'Reviews'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: ['#FFCE56', '#36A2EB', '#FF6384'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });

        function fetchData(brandId = null) {
            $.ajax({
                url: '{{ route('admin.get-brand-data') }}',
                type: 'POST',
                data: {
                    brand_id: brandId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    enquiriesTestRideReviewChart.data.datasets[0].data = [
                        response.offerRequests,
                        response.testRideRequests,
                        response.reviews
                    ];

                enquiriesTestRideReviewChart.data.labels = brandId
                    ? ['Completed Offers (Brand)', 'Completed Test Rides (Brand)', 'Verified Reviews (Brand)']
                    : ['Offers (Total)', 'Test Rides (Total)', 'Reviews (Total)'];

                    enquiriesTestRideReviewChart.update();
                },
                error: function(error) {
                    console.log("Error:", error);
                }
            });
        }

        fetchData();

        $('#brand_id').change(function() {
            var brandId = $(this).val();
            fetchData(brandId);
        });
    });






    </script>

    <div class="row mt-4">
        <div class="col-md-12">
         <!-- New Requests Grid Section  Start -->
       <x-crud-index title="New Requests" >
        <div class="col-md-12" style="">
            <div class="pt-60">
                <ul class="w-100 enquiry-head nav nav-tabs tabs" role="tablist">
                    <li class="nav-item tab enquiry-title">
                        <a class="nav-link active" id="test-ride-tab-2" data-toggle="tab" href="#test-ride-2" role="tab" aria-controls="directory-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                            <span class="d-none d-sm-block">Test Rides</span>
                        </a>
                    </li>
                    <li class="nav-item tab enquiry-title">
                        <a class="nav-link " id="offers-tab-2" data-toggle="tab" href="#offers-2" role="tab" aria-controls="classified-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                            <span class="d-none d-sm-block">Offers</span>
                        </a>
                    </li>
                    <li class="nav-item tab enquiry-title">
                        <a class="nav-link " id="loans-tab-2" data-toggle="tab" href="#loans-2" role="tab" aria-controls="helpline-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                            <span class="d-none d-sm-block">Loans</span>
                        </a>
                    </li>

                    {{-- <li class="nav-item tab enquiry-title">
                        <a class="nav-link" id="tag-tab-2" data-toggle="tab" href="#tag-2" role="tab" aria-controls="tag-2" aria-selected="false">
                            <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                            <span class="d-none d-sm-block">Tags</span>
                        </a>
                    </li> --}}
                </ul>
                <div class="w-100 tab-content mt-3">
                    <div class="tab-pane show active" id="test-ride-2" role="tabpanel" aria-labelledby="test-ride-tab-2">

                        {!! $testDriveDataGrid->render() !!}

                        <div class="mt-3 text-right">
                            <a href="{{ route('admin.test-ride-requests.index') }}" >View All</a>
                        </div>

                    </div>
                    <div class="tab-pane show " id="offers-2" role="tabpanel" aria-labelledby="offers-tab-2">

                        {!! $offerDataGrid->render() !!}

                        <div class="mt-3 text-right">
                            <a href="{{ route('admin.offer-requests.index') }}" >View All</a>
                        </div>

                    </div>
                    <div class="tab-pane show " id="loans-2" role="tabpanel" aria-labelledby="loans-tab-2">

                        {!! $loanDataGrid->render() !!}

                        <div class="mt-3 text-right">
                            <a href="{{ route('admin.loan-requests.index') }}" >View All</a>
                        </div>

                    </div>
                    {{-- <div class="tab-pane show " id="tag-2" role="tabpanel" aria-labelledby="tag-tab-2">

                        {!! $newTagRequests->render() !!}

                    </div> --}}
                </div>
            </div>
        </div>
    </x-crud-index>
     <!-- New Requests Grid Section End -->
    </div>
    </div>

     {{--   <div class="row" style="margin-top: 20px;">
            <div class="col-sm-8">
                <div class="mini-stat clearfix bx-shadow bg-white ">
                    <h4 class="text-dark text-4  m-0 font-weight-bold " style="text-align:center">Statistics ( {{ $currentYear }} ) </h4>
                    <h4 class="text-dark " ></h4>
                    <div class="mini-stat-info  text-dark">
                        <p class="text-dark text-4  m-0" style="text-align:left">
                            Users : {{ $customerEntriesCreated }}
                        </p>
                        <p class="text-dark text-4  m-0" style="text-align:left">
                            Directory : {{ $profileEntriesCreated }}
                        </p>
                        <p class="text-dark text-4  m-0" style="text-align:left">
                            Classifieds : {{ $classifiedEntriesCreated }}
                        </p>
                        <p class="text-dark text-4  m-0" style="text-align:left">
                            Q & A : {{ $helplineEntriesCreated }}
                        </p>
                        <p class="text-dark text-4  m-0" style="text-align:left">
                            News Feed : {{ $newsEntriesCreated }}
                        </p>
                    </div>
                    <div class="panel-body" align="center" style="margin-top:20px;margin-bottom:30px;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">

                <div class="mini-stat clearfix bx-shadow bg-white " align="center">
                    <h4 class="text-dark text-4  m-0 font-weight-bold " >Business Users</h4>
                    <h4 class="text-dark " >{{ $totalBusinessUser }}</h4>
                        <div class="mini-stat-info  text-dark">
                            <p class="text-dark text-4  m-0" style="text-align:left">
                                Me Premium : {{ $premium }}
                            </p>
                            <p class="text-dark text-4  m-0" style="text-align:left">
                                Me Booster : {{ $standard }}
                            </p>
                            <p class="text-dark text-4  m-0" style="text-align:left">
                                Me Starter : {{ $basic }}
                            </p>
                        </div>
                    <div class="panel-body" align="center" style="margin-top:20px;margin-bottom:70px;">
                        <canvas id="doughnutChart" width="250" height="250" ></canvas>
                    </div>
                </div>
            </div><br>
        </div>
        <!-- Chart Section  End -->

       <!-- New Requests Grid Section  Start -->
       <x-crud-index title="New Requests" >
            <div class="col-md-12" style="margin-top: 20px;">
                <div class="pt-60">
                    <ul class="w-100 enquiry-head nav nav-tabs tabs" role="tablist">
                        <li class="nav-item tab enquiry-title">
                            <a class="nav-link active" id="directory-tab-2" data-toggle="tab" href="#directory-2" role="tab" aria-controls="directory-2" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                                <span class="d-none d-sm-block">Directory</span>
                            </a>
                        </li>
                        <li class="nav-item tab enquiry-title">
                            <a class="nav-link " id="classified-tab-2" data-toggle="tab" href="#classified-2" role="tab" aria-controls="classified-2" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                                <span class="d-none d-sm-block">Classifieds</span>
                            </a>
                        </li>
                        <li class="nav-item tab enquiry-title">
                            <a class="nav-link " id="helpline-tab-2" data-toggle="tab" href="#helpline-2" role="tab" aria-controls="helpline-2" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                                <span class="d-none d-sm-block">Q & A</span>
                            </a>
                        </li>

                        <li class="nav-item tab enquiry-title">
                            <a class="nav-link" id="tag-tab-2" data-toggle="tab" href="#tag-2" role="tab" aria-controls="tag-2" aria-selected="false">
                                <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                                <span class="d-none d-sm-block">Tags</span>
                            </a>
                        </li>
                    </ul>
                    <div class="w-100 tab-content">
                        <div class="tab-pane show active" id="directory-2" role="tabpanel" aria-labelledby="directory-tab-2">

                            {!! $newDirectories->render() !!}

                        </div>
                        <div class="tab-pane show " id="classified-2" role="tabpanel" aria-labelledby="classified-tab-2">

                            {!! $newClassifiedAds->render() !!}

                        </div>
                        <div class="tab-pane show " id="helpline-2" role="tabpanel" aria-labelledby="helpline-tab-2">

                            {!! $newHelplineQuestions->render() !!}

                        </div>
                        <div class="tab-pane show " id="tag-2" role="tabpanel" aria-labelledby="tag-tab-2">

                            {!! $newTagRequests->render() !!}

                        </div>
                    </div>
                </div>
            </div>
        </x-crud-index>
         <!-- New Requests Grid Section End -->

        <x-slot name="scripts">

            <script type="application/javascript">

               let myLineChart;

                // Doughnut Chart For Plan Types
                function PieChart(selector, data, title)
                {
                    new Chart($(selector),
                    {
                        type: 'doughnut',
                        data: data,
                        options:
                        {
                            legend:
                            {
                                display: true
                            },
                            responsive: true,
                            title:
                            {
                                display: true,
                                text: title,
                                fontSize: 15
                            }
                        }
                    });
                }
                $.ajax({
                    url: "{{ route('admin.dashboard.get-plan-counts') }}",
                    data:{},
                    success: ( data ) => {
                        PieChart('#doughnutChart', data, "");
                    }
                });

                $.ajax({
                    type:'get',
                    url: "{{ route('admin.dashboard.get-line-charts') }}",
                    data:{},
                    success: ( data ) => {
                        console.log(data);
                        LineChart('#lineChart', data, "");
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });

                function LineChart(selector, data, title)
                {
                    if (myLineChart)
                    {
                            myLineChart.destroy();
                    }
                    myLineChart = new Chart($(selector),
                    {
                        type: 'line',
                        data:
                        {
                            datasets: [{
                                data: data.customers,
                                label:'Customers',
                                fill: false,
                                borderWidth: 2,
                                pointRadius:3,
                                tension:0,
                                borderColor: 'red',
                                backgroundColor: 'red'
                            },
                            {
                                data: data.businessUsers,
                                label:'Directory',
                                fill: false,
                                borderWidth: 2,
                                pointRadius:3,
                                tension:0,
                                borderColor: 'darkorange',
                                backgroundColor: 'darkorange'
                            },
                            {
                                data: data.classifieds,
                                label:'Classifieds',
                                fill: false,
                                borderWidth: 2,
                                pointRadius:3,
                                tension:0,
                                borderColor: 'cyan',
                                backgroundColor: 'cyan'
                            },
                            {
                                data: data.helplineQns,
                                label:'Q & A',
                                fill: false,
                                borderWidth: 2,
                                pointRadius:3,
                                tension:0,
                                borderColor: 'blue',
                                backgroundColor: 'blue'
                            },
                            {
                                data: data.news,
                                label:'News Feed',
                                fill: false,
                                borderWidth: 2,
                                pointRadius:3,
                                tension:0,
                                borderColor: 'green',
                                backgroundColor: 'green'
                            }],
                            labels:data.label
                        },

                        options:
                        {
                            responsive: true,
                            legend: {
                                display: true,
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                    },
                                    scaleLabel:{
                                        display: true,

                                    },
                                }],
                                xAxes: [{
                                    scaleLabel:{
                                        display: true,
                                        labelString: 'Months'
                                    },
                                }]
                            },
                            title: {
                                display: true,
                                text: title,
                                fontSize: 15
                            },
                        }
                    });
                }


               function show(identifier)
               {
                    const url = "{{ route('admin.pending-tag.show') }}";
                    window.location.href = url + '?id=' + $(identifier).data().id;
               }

            </script>

        </x-slot>--}}

    </x-admin-layout>
