@extends('layouts.master')

@section('title', 'Tournament Records - CrickTracker')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h2 class="text-dark fw-bold mb-0">
            <i class="fa-solid fa-trophy text-primary me-2"></i>Tournament Records
        </h2>
        
        <!-- Cleaned Filter Header Badges -->
        <div class="d-flex gap-2">
            <div class="filter-pill">
                <span>Format</span><strong>T20</strong>
            </div>
            <div class="filter-pill">
                <span>Year</span><strong>2026</strong>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column Sidebar Navigation Filters -->
        <div class="col-lg-3 mb-4">
            <div class="dashboard-card border-0 bg-white shadow-sm p-3 mb-3">
                <div class="stat-menu-title mb-2 text-uppercase">Quick Links</div>
                <a href="#batting-section" class="stat-menu-item active mb-1"><i class="fa-solid fa-batting-averages"></i> Batting Leaders</a>
                <a href="#bowling-section" class="stat-menu-item"><i class="fa-solid fa-circle-dot text-success"></i> Bowling Leaders</a>
            </div>

            <div class="dashboard-card border-0 bg-white shadow-sm p-3">
                <div class="stat-menu-title mb-2 text-uppercase">Tracking Filters</div>
                <a href="#batting-section" class="stat-menu-item d-flex justify-content-between align-items-center mb-1">
                    <span>Most Runs</span><i class="fa-solid fa-chevron-right fs-xs"></i>
                </a>
                <a href="#bowling-section" class="stat-menu-item d-flex justify-content-between align-items-center">
                    <span>Most Wickets</span><i class="fa-solid fa-chevron-right fs-xs"></i>
                </a>
            </div>
        </div>

        <!-- Right Column Data Records Panels -->
        <div class="col-lg-9">
            
            <!-- SECTION 1: BATTING LEADERS RECORDS (10 PLAYERS) -->
            <div id="batting-section" class="dashboard-card border-0 bg-white shadow-sm p-4 mb-4">
                <h5 class="table-section-title mb-3 fw-bold">Batting Records (Most Runs Leaders)</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border">
                        <thead class="bg-dark text-white text-uppercase small">
                            <tr>
                                <th scope="col" class="py-2.5 px-3">Player</th>
                                <th scope="col" class="py-2.5 text-center">Team</th>
                                <th scope="col" class="py-2.5 text-center">Matches</th>
                                <th scope="col" class="py-2.5 text-center">Inns</th>
                                <th scope="col" class="py-2.5 text-center">Runs</th>
                                <th scope="col" class="py-2.5 text-center">HS</th>
                                <th scope="col" class="py-2.5 text-center">Avg</th>
                                <th scope="col" class="py-2.5 text-center">SR</th>
                                <th scope="col" class="py-2.5 text-center px-3">100 / 50</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Tanvir Rahman</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">CSE</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center">5</td>
                                <td class="text-center fw-bold text-primary">248</td>
                                <td class="text-center">84*</td>
                                <td class="text-center font-monospace">62.00</td>
                                <td class="text-center font-monospace">145.88</td>
                                <td class="text-center px-3">0 / 2</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Rony Ahsan</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">ME</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center">5</td>
                                <td class="text-center fw-bold text-primary">215</td>
                                <td class="text-center">102</td>
                                <td class="text-center font-monospace">43.00</td>
                                <td class="text-center font-monospace">152.48</td>
                                <td class="text-center px-3">1 / 1</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Sajid Hasan</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">ECE</span></td>
                                <td class="text-center fw-semibold">4</td>
                                <td class="text-center">4</td>
                                <td class="text-center fw-bold text-primary">198</td>
                                <td class="text-center">76</td>
                                <td class="text-center font-monospace">49.50</td>
                                <td class="text-center font-monospace">138.46</td>
                                <td class="text-center px-3">0 / 2</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Asif Mahmud</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">LE</span></td>
                                <td class="text-center fw-semibold">4</td>
                                <td class="text-center">4</td>
                                <td class="text-center fw-bold text-primary">185</td>
                                <td class="text-center">64*</td>
                                <td class="text-center font-monospace">61.66</td>
                                <td class="text-center font-monospace">130.28</td>
                                <td class="text-center px-3">0 / 1</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Naimur Rahman</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">EEE</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center">4</td>
                                <td class="text-center fw-bold text-primary">176</td>
                                <td class="text-center">58</td>
                                <td class="text-center font-monospace">44.00</td>
                                <td class="text-center font-monospace">128.47</td>
                                <td class="text-center px-3">0 / 1</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Mahfuz Anam</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">CE</span></td>
                                <td class="text-center fw-semibold">4</td>
                                <td class="text-center">4</td>
                                <td class="text-center fw-bold text-primary">164</td>
                                <td class="text-center">92</td>
                                <td class="text-center font-monospace">41.00</td>
                                <td class="text-center font-monospace">160.78</td>
                                <td class="text-center px-3">0 / 1</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Sakib Al Amin</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">TE</span></td>
                                <td class="text-center fw-semibold">3</td>
                                <td class="text-center">3</td>
                                <td class="text-center fw-bold text-primary">142</td>
                                <td class="text-center">55*</td>
                                <td class="text-center font-monospace">71.00</td>
                                <td class="text-center font-monospace">124.56</td>
                                <td class="text-center px-3">0 / 1</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Zahid Hasan</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">URP</span></td>
                                <td class="text-center fw-semibold">4</td>
                                <td class="text-center">4</td>
                                <td class="text-center fw-bold text-primary">138</td>
                                <td class="text-center">48</td>
                                <td class="text-center font-monospace">34.50</td>
                                <td class="text-center font-monospace">118.96</td>
                                <td class="text-center px-3">0 / 0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Imtiaz Ahmed</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">CSE</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center">5</td>
                                <td class="text-center fw-bold text-primary">135</td>
                                <td class="text-center">42*</td>
                                <td class="text-center font-monospace">33.75</td>
                                <td class="text-center font-monospace">132.35</td>
                                <td class="text-center px-3">0 / 0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Rakibul Islam</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">ME</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center">5</td>
                                <td class="text-center fw-bold text-primary">129</td>
                                <td class="text-center">51</td>
                                <td class="text-center font-monospace">25.80</td>
                                <td class="text-center font-monospace">140.22</td>
                                <td class="text-center px-3">0 / 1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: BOWLING LEADERS RECORDS (10 PLAYERS) -->
            <div id="bowling-section" class="dashboard-card border-0 bg-white shadow-sm p-4">
                <h5 class="table-section-title mb-3 fw-bold">Bowling Records (Most Wickets Leaders)</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border">
                        <thead class="bg-dark text-white text-uppercase small">
                            <tr>
                                <th scope="col" class="py-2.5 px-3">Bowler</th>
                                <th scope="col" class="py-2.5 text-center">Team</th>
                                <th scope="col" class="py-2.5 text-center">Matches</th>
                                <th scope="col" class="py-2.5 text-center">Wickets</th>
                                <th scope="col" class="py-2.5 text-center">Runs Conc</th>
                                <th scope="col" class="py-2.5 text-center">Best Bowling</th>
                                <th scope="col" class="py-2.5 text-center">Economy</th>
                                <th scope="col" class="py-2.5 text-center px-3">5w</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Mustafizur Rahman</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">CSE</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center fw-bold text-success">12</td>
                                <td class="text-center">112</td>
                                <td class="text-center font-monospace">4/15</td>
                                <td class="text-center font-monospace">5.60</td>
                                <td class="text-center px-3">0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Kazi Ariful</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">EEE</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center fw-bold text-success">10</td>
                                <td class="text-center">134</td>
                                <td class="text-center font-monospace">5/21</td>
                                <td class="text-center font-monospace">6.70</td>
                                <td class="text-center px-3">1</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Sadman Sakib</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">ME</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center fw-bold text-success">9</td>
                                <td class="text-center">120</td>
                                <td class="text-center font-monospace">3/18</td>
                                <td class="text-center font-monospace">6.00</td>
                                <td class="text-center px-3">0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Mridul Hasan</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">ECE</span></td>
                                <td class="text-center fw-semibold">4</td>
                                <td class="text-center fw-bold text-success">8</td>
                                <td class="text-center">98</td>
                                <td class="text-center font-monospace">3/22</td>
                                <td class="text-center font-monospace">6.12</td>
                                <td class="text-center px-3">0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Tariqul Islam</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">CE</span></td>
                                <td class="text-center fw-semibold">4</td>
                                <td class="text-center fw-bold text-success">8</td>
                                <td class="text-center">142</td>
                                <td class="text-center font-monospace">4/30</td>
                                <td class="text-center font-monospace">8.87</td>
                                <td class="text-center px-3">0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Fahim Faisal</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">LE</span></td>
                                <td class="text-center fw-semibold">4</td>
                                <td class="text-center fw-bold text-success">7</td>
                                <td class="text-center">88</td>
                                <td class="text-center font-monospace">3/12</td>
                                <td class="text-center font-monospace">5.50</td>
                                <td class="text-center px-3">0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Abrar Zawad</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">TE</span></td>
                                <td class="text-center fw-semibold">3</td>
                                <td class="text-center fw-bold text-success">6</td>
                                <td class="text-center">72</td>
                                <td class="text-center font-monospace">3/20</td>
                                <td class="text-center font-monospace">6.00</td>
                                <td class="text-center px-3">0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Niaz Morshed</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">URP</span></td>
                                <td class="text-center fw-semibold">4</td>
                                <td class="text-center fw-bold text-success">5</td>
                                <td class="text-center">115</td>
                                <td class="text-center font-monospace">2/19</td>
                                <td class="text-center font-monospace">7.18</td>
                                <td class="text-center px-3">0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Shariar Nafis</td>
                                <td class="text-center"><span class="badge bg-primary-subtle text-primary fw-bold">CSE</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center fw-bold text-success">5</td>
                                <td class="text-center">130</td>
                                <td class="text-center font-monospace">2/24</td>
                                <td class="text-center font-monospace">6.50</td>
                                <td class="text-center px-3">0</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 px-3 fw-bold text-dark">Ariful Islam</td>
                                <td class="text-center"><span class="badge bg-secondary-subtle text-secondary fw-bold">ME</span></td>
                                <td class="text-center fw-semibold">5</td>
                                <td class="text-center fw-bold text-success">4</td>
                                <td class="text-center">122</td>
                                <td class="text-center font-monospace">2/30</td>
                                <td class="text-center font-monospace">6.10</td>
                                <td class="text-center px-3">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection