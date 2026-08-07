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
            <h1 style="margin: 0; font-size: 26px; line-height: 1.1">Thanks for reaching out</h1>
            <p
                style="margin: 12px 0 0; color: rgba(255, 255, 255, 0.86); max-width: 520px"
            >Hi {{ $data['name'] }}, we received your message and will reply shortly.</p>
        </div>
        <div style="padding: 32px">
            <p style="margin: 0 0 20px; color: #475569; line-height: 1.7">We received the following information from your contact request. If anything looks incorrect, simply reply to this message and we will update it.</p>

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
                            width: 140px;
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
                    padding: 22px;
                    background: #ecfdf5;
                    border-radius: 18px;
                    border: 1px solid #d1fae5;
                    color: #065f46;
                "
            >
                <p style="margin: 0; font-weight: 700">What happens next</p>
                <p
                    style="margin: 8px 0 0; color: #475569"
                >One of our team members will review your request and send a reply as soon as possible. If you need to make a change, reply to this message.</p>
            </div>

            <p
                style="margin: 30px 0 0; color: #475569"
            >Thanks,<br />{{ config('app.name') }} Team</p>
        </div>
    </div>
</div>
