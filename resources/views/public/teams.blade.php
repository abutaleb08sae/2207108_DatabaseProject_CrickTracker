@extends('layouts.master')

@section('title', 'Points Table Standings - CrickTracker')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center gap-3 mb-4">
        <h2 class="text-dark fw-bold mb-0">
            <i class="fa-solid fa-table-list text-primary me-2"></i>Points Table Standings
        </h2>
    </div>
    
    <div class="dashboard-card border-0 bg-white shadow-sm p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white text-uppercase small">
                    <tr>
                        <th scope="col" class="py-3 px-4" style="color: #94a3b8;">Department Entity</th>
                        <th scope="col" class="py-3 text-center" style="color: #94a3b8;">Played</th>
                        <th scope="col" class="py-3 text-center text-success">Won</th>
                        <th scope="col" class="py-3 text-center text-danger">Lost</th>
                        <th scope="col" class="py-3 text-center text-warning">Tied</th>
                        <th scope="col" class="py-3 text-center text-info">Net NRR</th>
                        <th scope="col" class="py-3 text-center px-4" style="color: #38bdf8;">Points Summary</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <!-- SAMPLE ROW 1: CSE -->
                    <tr>
                        <td class="py-3 px-4 fw-bold text-dark">
                            <i class="fa-solid fa-shield-halved text-primary me-2"></i>CSE <span class="text-muted small fw-normal d-block d-sm-inline ms-sm-2">(Computer Science & Engineering)</span>
                        </td>
                        <td class="text-center fw-semibold">3</td>
                        <td class="text-center text-success fw-bold">2</td>
                        <td class="text-center text-danger fw-semibold">1</td>
                        <td class="text-center text-warning fw-semibold">0</td>
                        <td class="text-center text-info font-monospace fw-bold">+1.245</td>
                        <td class="text-center px-4"><span class="badge bg-primary fs-6 px-3 py-1.5 rounded-pill">4 pts</span></td>
                    </tr>

                    <!-- SAMPLE ROW 2: ME -->
                    <tr>
                        <td class="py-3 px-4 fw-bold text-dark">
                            <i class="fa-solid fa-shield-halved text-primary me-2"></i>ME <span class="text-muted small fw-normal d-block d-sm-inline ms-sm-2">(Mechanical Engineering)</span>
                        </td>
                        <td class="text-center fw-semibold">3</td>
                        <td class="text-center text-success fw-bold">2</td>
                        <td class="text-center text-danger fw-semibold">1</td>
                        <td class="text-center text-warning fw-semibold">0</td>
                        <td class="text-center text-info font-monospace fw-bold">+0.580</td>
                        <td class="text-center px-4"><span class="badge bg-primary fs-6 px-3 py-1.5 rounded-pill">4 pts</span></td>
                    </tr>

                    <!-- SAMPLE ROW 3: LE -->
                    <tr>
                        <td class="py-3 px-4 fw-bold text-dark">
                            <i class="fa-solid fa-shield-halved text-primary me-2"></i>LE <span class="text-muted small fw-normal d-block d-sm-inline ms-sm-2">(Leather Engineering)</span>
                        </td>
                        <td class="text-center fw-semibold">2</td>
                        <td class="text-center text-success fw-bold">2</td>
                        <td class="text-center text-danger fw-semibold">0</td>
                        <td class="text-center text-warning fw-semibold">0</td>
                        <td class="text-center text-info font-monospace fw-bold">+0.420</td>
                        <td class="text-center px-4"><span class="badge bg-primary fs-6 px-3 py-1.5 rounded-pill">4 pts</span></td>
                    </tr>

                    <!-- SAMPLE ROW 4: TE -->
                    <tr>
                        <td class="py-3 px-4 fw-bold text-dark">
                            <i class="fa-solid fa-shield-halved text-primary me-2"></i>TE <span class="text-muted small fw-normal d-block d-sm-inline ms-sm-2">(Textile Engineering)</span>
                        </td>
                        <td class="text-center fw-semibold">2</td>
                        <td class="text-center text-success fw-bold">1</td>
                        <td class="text-center text-danger fw-semibold">1</td>
                        <td class="text-center text-warning fw-semibold">0</td>
                        <td class="text-center text-info font-monospace fw-bold">+0.120</td>
                        <td class="text-center px-4"><span class="badge bg-primary fs-6 px-3 py-1.5 rounded-pill">2 pts</span></td>
                    </tr>

                    <!-- SAMPLE ROW 5: ECE -->
                    <tr>
                        <td class="py-3 px-4 fw-bold text-dark">
                            <i class="fa-solid fa-shield-halved text-primary me-2"></i>ECE <span class="text-muted small fw-normal d-block d-sm-inline ms-sm-2">(Electronics & Communication Engineering)</span>
                        </td>
                        <td class="text-center fw-semibold">3</td>
                        <td class="text-center text-success fw-bold">1</td>
                        <td class="text-center text-danger fw-semibold">2</td>
                        <td class="text-center text-warning fw-semibold">0</td>
                        <td class="text-center text-info font-monospace fw-bold">-0.310</td>
                        <td class="text-center px-4"><span class="badge bg-primary fs-6 px-3 py-1.5 rounded-pill">2 pts</span></td>
                    </tr>

                    <!-- SAMPLE ROW 6: EEE -->
                    <tr>
                        <td class="py-3 px-4 fw-bold text-dark">
                            <i class="fa-solid fa-shield-halved text-primary me-2"></i>EEE <span class="text-muted small fw-normal d-block d-sm-inline ms-sm-2">(Electrical & Electronic Engineering)</span>
                        </td>
                        <td class="text-center fw-semibold">2</td>
                        <td class="text-center text-success fw-bold">0</td>
                        <td class="text-center text-danger fw-semibold">2</td>
                        <td class="text-center text-warning fw-semibold">0</td>
                        <td class="text-center text-info font-monospace fw-bold">-0.890</td>
                        <td class="text-center px-4"><span class="badge bg-primary fs-6 px-3 py-1.5 rounded-pill">0 pts</span></td>
                    </tr>

                    <!-- SAMPLE ROW 7: CE -->
                    <tr>
                        <td class="py-3 px-4 fw-bold text-dark">
                            <i class="fa-solid fa-shield-halved text-primary me-2"></i>CE <span class="text-muted small fw-normal d-block d-sm-inline ms-sm-2">(Civil Engineering)</span>
                        </td>
                        <td class="text-center fw-semibold">3</td>
                        <td class="text-center text-success fw-bold">0</td>
                        <td class="text-center text-danger fw-semibold">3</td>
                        <td class="text-center text-warning fw-semibold">0</td>
                        <td class="text-center text-info font-monospace fw-bold">-1.450</td>
                        <td class="text-center px-4"><span class="badge bg-primary fs-6 px-3 py-1.5 rounded-pill">0 pts</span></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection