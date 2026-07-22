<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>
        Payroll Slip
    </title>

    <style>

        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        table {
            width:100%;
            border-collapse: collapse;
        }

        td, th {
            border:1px solid #ddd;
            padding:8px;
        }

    </style>

</head>

<body>

    <h1>
        VELOURE BEAUTY HOUSE
    </h1>

    <h2>
        Slip Gaji
    </h2>

    <p>

        <strong>Karyawan:</strong>

        {{ $payroll->employee?->full_name }}

    </p>

    <p>

        <strong>Kode:</strong>

        {{ $payroll->employee?->employee_code }}

    </p>

    <p>

        <strong>Periode:</strong>

        {{ $payroll->month }}/{{ $payroll->year }}

    </p>

    <table>

        <thead>

            <tr>

                <th>Deskripsi</th>

                <th>Tipe</th>

                <th>Nominal</th>

            </tr>

        </thead>

        <tbody>

            @foreach(
                $payroll->details
                as $detail
            )

                <tr>

                    <td>
                        {{ $detail->description }}
                    </td>

                    <td>
                        {{ strtoupper(
                            $detail->type
                        ) }}
                    </td>

                    <td>

                        Rp

                        {{
                            number_format(
                                $detail->amount
                            )
                        }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

    <br>

    <table>

        <tr>

            <td>
                Gross Salary
            </td>

            <td>

                Rp

                {{
                    number_format(
                        $payroll->gross_salary
                    )
                }}

            </td>

        </tr>

        <tr>

            <td>
                Net Salary
            </td>

            <td>

                Rp

                {{
                    number_format(
                        $payroll->net_salary
                    )
                }}

            </td>

        </tr>

    </table>

</body>

</html>