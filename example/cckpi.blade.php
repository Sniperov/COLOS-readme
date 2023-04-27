<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        table {
            width: 80%;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .text-center {
            text-align: center;
        }
        .target {
            color: #00b050;
        }
        #turquoise {
            background: #92CDDC;
        }
        #blue {
            background: #8DB4E2;
        }
        #red {
            background: #C0504D;
        }
        #light-green {
            background: #C4D79B;
        }
        #purple {
            background: #B1A0C7;
        }
        #orange {
            background: #F79646;
        }
        #dark-blue {
            background: #366092;
        }
        #skiny {
            background: #DA9694;
        }
        #text-blue {
            color: #8DB4E2;
        }
    </style>

    @if ($params['Type'] == 'Monthly')
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
        google.charts.load("current", {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
            [
                'Element',
                '"Clean" items (without pre-inspection)',
                '"Clean" items (with pre-inspection)',
                '"Clean" items subject to COC',
                'Other NMT',
                'Lack of TDS',
                'Discrepancies goods vs documents',
                'Risk System Program Astana-1 (red and yellow corridor)'
            ],
            [
                '',
                {{ $cckpis[0]->CleanItemsWithoutPreInspection_Quantity }},
                {{ $cckpis[0]->CleanItemsWithPreInspection_Quantity }},
                {{ $cckpis[0]->CleanItemsSubjectToCOC_Quantity }},
                {{ $cckpis[0]->OtherNMT_Quantity }},
                {{ $cckpis[0]->LackOfTDS_Quantity }},
                {{ $cckpis[0]->DiscrepanciesGoodsVsDocuments_Quantity }},
                {{ $cckpis[0]->RiskSystemProgramAstana1_Quantity }}
            ]
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                }, 2, {
                    calc: "stringify",
                    sourceColumn: 2,
                    type: "string",
                    role: "annotation"
                }, 3, {
                    calc: "stringify",
                    sourceColumn: 3,
                    type: "string",
                    role: "annotation"
                }, 4, {
                    calc: "stringify",
                    sourceColumn: 4,
                    type: "string",
                    role: "annotation"
                }, 5, {
                    calc: "stringify",
                    sourceColumn: 5,
                    type: "string",
                    role: "annotation"
                }, 6, {
                    calc: "stringify",
                    sourceColumn: 6,
                    type: "string",
                    role: "annotation"
                }, 7, {
                    calc: "stringify",
                    sourceColumn: 7,
                    type: "string",
                    role: "annotation"
                }
            ]);

            var options = {
                title: "",
                width: 1000,
                height: 400,
                legend: { position: "right" },
                colors: [
                    "#8DB4E2",
                    "#C0504D",
                    "#C4D79B",
                    "#B1A0C7",
                    "#F79646",
                    "#366092",
                    "#DA9694"
                ],
                chartArea: {
                    height: '85%',
                    right: '20%'
                }
            };
            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
            chart.draw(view, options);
        }
        </script>
    @endif
</head>

<?php
    $headerColspan = 4;
    if ($params['Type'] == 'Yearly') {
        $headerColspan = 49;
    }
    if ($params['Type'] == 'Monthly') {
        $headerColspan = 4;
    }
    if ($params['Type'] == 'Monthly Comparation') {
        $headerColspan = 7;
    }
?>

<body class="text-center">
    <h2>Custom Clearance {{ $params['Type'] }} Report</h2>

    <table class="text-center" align="center" style="width:100%">
        <thead>
        <th style="width: 8%" id="turquoise"><b>Description</b></th>
        @foreach ($cckpis as $cckpi)
        <th style="width: 8%" colspan="3" id="turquoise"><b>{{ Carbon\Carbon::createFromDate($cckpi->Year, $cckpi->Month)->format('F (Y)') }}</b></th>
        @endforeach
        </thead>
        <tbody>
        <tr>
            <td>Days(overall)</td>
            @foreach ($cckpis as $cckpi)
                <td colspan="3">{{ $cckpi->DaysOverall }}</td>
            @endforeach
        </tr>
        <tr>
            <td>CCD-s issued</td>
            @foreach ($cckpis as $cckpi)
                <td colspan="3">{{ $cckpi->CCDissued }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Jobs</td>
            @foreach ($cckpis as $cckpi)
                <td colspan="3">{{ $cckpi->Jobs }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Line items</td>
            @foreach ($cckpis as $cckpi)
                <td colspan="3">{{ $cckpi->LineItems }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Pre - Inspection arranged</td>
            @foreach ($cckpis as $cckpi)
                <td colspan="3">{{ $cckpi->PreInspectionArranged }}</td>
            @endforeach
        </tr>
        </tbody>

        <thead>
            <th colspan="{{ $headerColspan }}">
                <h2 id="text-blue">Detalization for internal analysis/areas for improvement</h2>
            </th>
        </thead>

        <thead>
            <th style="width: 8%" id="turquoise">Descripton</th>
            @foreach ($cckpis as $cckpi)
                <th style="width: 8%" id="turquoise">Quantity of lines</th>
                <th style="width: 8%" id="turquoise">Percentage</th>
                <th style="width: 8%" id="turquoise">Average CC days</th>
            @endforeach
        </thead>
        <tbody>
            <tr>
                <td id="blue">"Clean" items (without pre-inspection)</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->CleanItemsWithoutPreInspection_Quantity }}</td>
                    <td>{{ $cckpi->CleanItemsWithoutPreInspection_Percent }}</td>
                    <td>{{ $cckpi->CleanItemsWithoutPreInspection_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td id="red">"Clean" items (with pre-inspection)</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->CleanItemsWithPreInspection_Quantity }}</td>
                    <td>{{ $cckpi->CleanItemsWithPreInspection_Percent }}</td>
                    <td>{{ $cckpi->CleanItemsWithPreInspection_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td id="light-green">"Clean" items subject to COC</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->CleanItemsSubjectToCOC_Quantity }}</td>
                    <td>{{ $cckpi->CleanItemsSubjectToCOC_Percent }}</td>
                    <td>{{ $cckpi->CleanItemsSubjectToCOC_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td id="purple">Other NMT</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->OtherNMT_Quantity }}</td>
                    <td>{{ $cckpi->OtherNMT_Percent }}</td>
                    <td>{{ $cckpi->OtherNMT_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td id="turquoise">PO issues</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->POIssues_Quantity }}</td>
                    <td>{{ $cckpi->POIssues_Percent }}</td>
                    <td>{{ $cckpi->POIssues_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td id="orange">Lack of TDS</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->LackOfTDS_Quantity }}</td>
                    <td>{{ $cckpi->LackOfTDS_Percent }}</td>
                    <td>{{ $cckpi->LackOfTDS_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td id="dark-blue">Discrepancies goods vs documents</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->DiscrepanciesGoodsVsDocuments_Quantity }}</td>
                    <td>{{ $cckpi->DiscrepanciesGoodsVsDocuments_Percent }}</td>
                    <td>{{ $cckpi->DiscrepanciesGoodsVsDocuments_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td id="skiny">Risk System Program Astana-1 (red and yellow corridor)</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->RiskSystemProgramAstana1_Quantity }}</td>
                    <td>{{ $cckpi->RiskSystemProgramAstana1_Percent }}</td>
                    <td>{{ $cckpi->RiskSystemProgramAstana1_Average }}</td>
                @endforeach
            </tr>
        </tbody>

        <thead>
            <th colspan="{{ $headerColspan }}">
                <h2 id="text-blue">Management KPI</h2>
            </th>
        </thead>

        <thead>
            <th style="width: 8%" id="turquoise">Descripton</th>
            @foreach ($cckpis as $cckpi)
                <th style="width: 8%" id="turquoise">Quantity of lines</th>
                <th style="width: 8%" id="turquoise">Percentage</th>
                <th style="width: 8%" id="turquoise">Average CC days</th>
            @endforeach
        </thead>
        <tbody>
            <tr>
                <td>Average "Clean" line items</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->AverageCleanLineItems_Quantity }}</td>
                    <td>{{ $cckpi->AverageCleanLineItems_Percent }}</td>
                    <td>{{ $cckpi->AverageCleanLineItems_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td>"Clean" items (with pre-inspection)</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->AverageCleanLineItemsWithPreInspection_Quantity }}</td>
                    <td>{{ $cckpi->AverageCleanLineItemsWithPreInspection_Percent }}</td>
                    <td>{{ $cckpi->AverageCleanLineItemsWithPreInspection_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Average line items subject to CoC only</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->AverageCleanItemsSubjectToCOC_Quantity }}</td>
                    <td>{{ $cckpi->AverageCleanItemsSubjectToCOC_Percent }}</td>
                    <td>{{ $cckpi->AverageCleanItemsSubjectToCOC_Average }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Average problematic items</td>
                @foreach ($cckpis as $cckpi)
                    <td>{{ $cckpi->AverageProblematicItems_Quantity }}</td>
                    <td>{{ $cckpi->AverageProblematicItems_Percent }}</td>
                    <td>{{ $cckpi->AverageProblematicItems_Average }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    @if ($params['Type'] == 'Monthly')
        <h2 class="text-center">KPI CC Quantity of line items</h2>
        <div id="columnchart_values" style="width: 1000px; height: 400px;"></div>
    @endif
</body>
</html>
