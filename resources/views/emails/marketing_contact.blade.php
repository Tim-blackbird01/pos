<div
    style="
        font-family:
            Inter,
            ui-sans-serif,
            system-ui,
            -apple-system,
            'Segoe UI',
            sans-serif;
        background: #f3faf7;
        padding: 24px;
        color: #0f172a;
    "
>
    <div
        style="
            max-width: 680px;
            margin: auto;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        "
    >
        <div
            style="
                background: linear-gradient(135deg, #065f46 0%, #10b981 100%);
                padding: 28px 32px;
                color: #ffffff;
            "
        >
            <h1 style="margin: 0; font-size: 26px; line-height: 1.1">
                New contact request received
            </h1>
            <p
                style="margin: 12px 0 0; color: rgba(255, 255, 255, 0.86); max-width: 520px"
            >A visitor submitted the contact form on {{ config('app.name', 'CraftSalesPOS') }}.</p>
        </div>
        <div style="padding: 32px">
            <table
                cellpadding="0"
                cellspacing="0"
                role="presentation"
                style="width: 100%; border-collapse: collapse"
            >
                <tr>
                    <td
                        style="
                            padding: 12px 0;
                            vertical-align: top;
                            width: 160px;
                            font-weight: 700;
                            color: #065f46;
                        "
                    >
                        Name
                    </td>
                    <td style="padding: 12px 0; color: #0f172a">{{ $data['name'] }}</td>
                </tr>
                <tr>
                    <td
                        style="
                            padding: 12px 0;
                            vertical-align: top;
                            font-weight: 700;
                            color: #065f46;
                        "
                    >
                        Email
                    </td>
                    <td style="padding: 12px 0; color: #0f172a">{{ $data['email'] }}</td>
                </tr>
                <tr>
                    <td
                        style="
                            padding: 12px 0;
                            vertical-align: top;
                            font-weight: 700;
                            color: #065f46;
                        "
                    >
                        Subject
                    </td>
                    <td style="padding: 12px 0; color: #0f172a">{{ $data['subject'] }}</td>
                </tr>
                <tr>
                    <td
                        style="
                            padding: 12px 0;
                            vertical-align: top;
                            font-weight: 700;
                            color: #065f46;
                        "
                    >
                        Message
                    </td>
                    <td style="padding: 12px 0; color: #0f172a; white-space: pre-wrap">
                        {{ $data['message'] }}
                    </td>
                </tr>
            </table>

            <div
                style="
                    margin-top: 28px;
                    padding: 20px;
                    background: #ecfdf5;
                    border-radius: 16px;
                    border: 1px solid #d1fae5;
                    color: #065f46;
                "
            >
                <p style="margin: 0; font-weight: 700">Reply details</p>
                <p
                    style="margin: 8px 0 0; color: #475569"
                >Respond directly to the sender at {{ $data['email'] }} to continue the conversation.</p>
            </div>
        </div>
    </div>
</div>
