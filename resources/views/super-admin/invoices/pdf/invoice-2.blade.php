<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>@lang('app.invoice') {{ $invoice->filename }}</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Invoice">

    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ storage_path('fonts/THSarabunNew_Bold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ storage_path('fonts/THSarabunNew_Bold_Italic.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ storage_path('fonts/THSarabunNew_Italic.ttf') }}") format('truetype');
        }

        @if($globalInvoiceSetting->is_chinese_lang)
        @font-face {
            font-family: SimHei;
            /*font-style: normal;*/
            font-weight: bold;
            src: url('{{ asset('fonts/simhei.ttf') }}') format('truetype');
        }
        @endif

        @php
            $font = '';
            if($globalInvoiceSetting->locale == 'ja') {
                $font = 'ipag';
            } else if($globalInvoiceSetting->locale == 'hi') {
                $font = 'hindi';
            } else if($globalInvoiceSetting->locale == 'th') {
                $font = 'THSarabunNew';
            } else if($globalInvoiceSetting->is_chinese_lang) {
                $font = 'SimHei';
            }else {
                $font = 'Verdana';
            }
        @endphp

        @if($globalInvoiceSetting->is_chinese_lang)
            body
        {
            font-weight: normal !important;
        }
        @endif
        * {
            font-family: {{$font}}, DejaVu Sans , sans-serif;
        }

        /* Reset styles */
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td,
        article,
        aside,
        canvas,
        details,
        embed,
        figure,
        figcaption,
        footer,
        header,
        hgroup,
        menu,
        nav,
        output,
        ruby,
        section,
        summary,
        time,
        mark,
        audio,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            /* font-family: Verdana, Arial, Helvetica, sans-serif; */
            /* font-size: 80%; */
            vertical-align: baseline;
        }

        html {
            line-height: 1;
        }

        ol,
        ul {
            list-style: none;
        }

        table {
            border-collapse: collapse;
        }

        caption,
        th,
        td {
            text-align: left;
            font-weight: normal;
            vertical-align: middle;
        }

        q,
        blockquote {
            quotes: none;
        }

        q:before,
        q:after,
        blockquote:before,
        blockquote:after {
            content: "";
            content: none;
        }

        a img {
            border: none;
        }

        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        main,
        menu,
        nav,
        section,
        summary {
            display: block;
        }

        /* Invoice styles */
        /**
         * DON'T override any styles for the <html> and <body> tags, as this may break the layout.
         * Instead wrap everything in one main <div id="container"> element where you may change
         * something like the font or the background of the invoice
         */
        html,
        body {
            /* MOVE ALONG, NOTHING TO CHANGE HERE! */
        }

        /**
         * IMPORTANT NOTICE: DON'T USE '!important' otherwise this may lead to broken print layout.
         * Some browsers may require '!important' in oder to work properly but be careful with it.
         */
        .clearfix {
            display: block;
            clear: both;
        }

        .hidden {
            display: none;
        }

        b,
        strong,
        .bold {
            font-weight: bold;
        }

        #container {
            font: normal 13px/1.4em 'Open Sans', Sans-serif;
            margin: 0 auto;
        }

        .invoice-top {
            color: #000000;
            padding: 40px 40px 10px 40px;
        }

        .invoice-body {
            padding: 10px 40px 40px 40px;
        }

        #memo .logo {
            float: left;
            margin-right: 20px;
        }

        #memo .logo img {
            height: 50px;
        }

        #memo .company-info {
            /*float: right;*/
            text-align: right;
        }

        #memo .company-info .company-name {
            font-size: 20px;
            text-align: right;
        }

        #memo .company-info .spacer {
            height: 15px;
            display: block;
        }

        #memo .company-info div {
            font-size: 12px;
            text-align: right;
            line-height: 18px;
        }

        #memo:after {
            content: '';
            display: block;
            clear: both;
        }

        #invoice-info {
            text-align: left;
            margin-top: 20px;
            line-height: 18px;
        }

        #invoice-info table {
            width: 30%;
        }

        #invoice-info>div {
            float: left;
        }

        #invoice-info>div>span {
            display: block;
            min-width: 100px;
            min-height: 18px;
            margin-bottom: 3px;
        }

        #invoice-info:after {
            content: '';
            display: block;
            clear: both;
        }

        #client-info {
            text-align: right;
            min-width: 220px;
            line-height: 18px;
        }

        #client-info>div {
            margin-bottom: 3px;
        }

        #client-info span {
            display: block;
        }

        #client-info>span {
            margin-bottom: 3px;
        }

        #invoice-title-number {
            margin-top: 30px;
        }

        #invoice-title-number #title {
            font-size: 35px;
        }

        #invoice-title-number #number {
            text-align: left;
            font-size: 20px;
        }

        table {
            table-layout: fixed;
        }

        table th,
        table td {
            vertical-align: top;
            word-break: keep-all;
            word-wrap: break-word;
        }

        #items .first-cell,
        #items table th:first-child,
        #items table td:first-child {
            width: 18px;
            text-align: right;
        }

        #items table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #000000
        }

        #items table th {
            font-weight: bold;
            padding: 12px 10px;
            text-align: right;
            border-bottom: 1px solid #444;
        }

        #items table th:nth-child(2) {
            width: 30%;
            text-align: left;
        }

        #items table th:last-child {
            text-align: right;
        }

        #items table td {
            border-right: 1px solid #b6b6b6;
            padding: 7px 10px;
            text-align: right;
        }

        #items table td:first-child {
            text-align: left;
        }

        #items table td:nth-child(2) {
            text-align: left;
        }

        #items table td:last-child {
            border-right: none !important;
        }

        #terms>div {
            min-height: 30px;
        }

        .payment-info {
            color: #707070;
            font-size: 12px;
        }

        .payment-info div {
            display: inline-block;
            min-width: 10px;
        }

        .ib_drop_zone {
            color: #F8ED31 !important;
            border-color: #F8ED31 !important;
        }

        .item-summary {
            font-size: 11px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        /**
         * If the printed invoice is not looking as expected you may tune up
         * the print styles (you can use !important to override styles)
         */
        @media print {
            /* Here goes your print styles */
        }

        .page_break {
            page-break-before: always;
        }

        .h3-border {
            border-bottom: 1px solid #AAAAAA;
        }

        table td.text-center {
            text-align: center;
        }

        table td.text-right {
            text-align: right;
        }

        #itemsPayment .first-cell,
        #itemsPayment table th:first-child,
        #itemsPayment table td:first-child {
            width: 18px;
            text-align: right;
        }

        #itemsPayment table {
            border-collapse: separate;
            width: 100%;
        }

        #itemsPayment table th {
            font-weight: bold;
            padding: 12px 10px;
            text-align: right;
            border-bottom: 1px solid #444;
            text-transform: uppercase;
        }

        #itemsPayment table th:nth-child(2) {
            width: 30%;
            text-align: left;
        }

        #itemsPayment table th:last-child {
            text-align: right;
        }

        #itemsPayment table td {
            border-right: 1px solid #b6b6b6;
            padding: 15px 10px;
            text-align: right;
        }

        #itemsPayment table td:first-child {
            text-align: left;
            /*border-right: none !important;*/
        }

        #itemsPayment table td:nth-child(2) {
            text-align: left;
        }

        #itemsPayment table td:last-child {
            border-right: none !important;
        }

        .word-break {
            word-wrap: break-word;
        }

        #notes {
            color: #767676;
            font-size: 11px;
        }

        @if($globalInvoiceSetting->locale == 'th')

            table td {
            font-weight: bold !important;
            font-size: 20px !important;
            }

            .description
            {
                font-weight: bold !important;
                font-size: 16px !important;
            }
        @endif

        .client-logo {
            height: 80px;
        }

        .client-logo-div {
            position: absolute;
            margin-left: -80px;
            margin-top: 30px;

        }


    </style>
</head>

<body>
    <div id="container" class="description">
        <div class="invoice-top">
            <section id="memo"  class="description">
                <div class="logo">
                    <img src="{{ $globalInvoiceSetting->logo_url }}" alt="{{$globalInvoiceSetting->billing_name}}" />
                </div>

                <div class="company-info description">
                    <span class="company-name">
                        {{ $globalInvoiceSetting->billing_name }}
                    </span>

                    <span class="spacer"></span>
                    @if($superadmin->company_phone)<div>{{ $superadmin->company_phone }}</div>@endif
                    <div>{!! nl2br($globalInvoiceSetting->billing_address) !!}</div>
                    @if(!is_null($globalInvoiceSetting->billing_tax_name))
                        <div>{{ $globalInvoiceSetting->billing_tax_name }}: {{ $globalInvoiceSetting->billing_tax_id }}</div>
                    @endif

                </div>

            </section>

            <section id="invoice-info"  class="description">
                <table>
                    <tr>
                        <td>@lang('superadmin.issue_date'):</td>
                        <td>{{ !is_null($invoice->pay_date) ? $invoice->pay_date->translatedFormat($company->date_format) : $invoice->created_at->translatedFormat($company->date_format) }}</td>
                    </tr>
                </table>

                <section id="invoice-title-number">
                    <span id="number"># {{ $invoice->invoice_number }}</span>
                </section>
            </section>

                <section id="client-info"  class="description">
                    <span>@lang('modules.invoices.billedTo'):</span>
                    <div>
                            <span class="bold">{{ $company->company_name }}</span>
                        </div>
                        <div class="mb-3">
                            <b>@lang('app.address') :</b>
                            <div>{!! nl2br($company->address) !!}</div>
                        </div>
                </section>


            <div class="clearfix"></div>
        </div>


        <div class="invoice-body">
            <section id="items"  class="description">

                <table cellpadding="0" cellspacing="0">

                    <tr>
                    <th>#</th>
                    <th class="description">@lang("app.description")</th>
                    <th class="description">@lang("app.date")</th>
                    <th class="description">@lang("app.amount") ({!! htmlentities($invoice->currency->currency_code) !!})</th>
                    </tr>

                    <tr data-iterate="item">
                        <td>1</td>
                        <!-- Don't remove this column as it's needed for the row commands -->
                        <td>
                            {{ $invoice->package->name  }} @if ($invoice->package->default != 'trial' && $invoice->package->default != 'lifetime')@lang('superadmin.'.$invoice->company->package_type) @endif
                        </td>
                        <td>{{ $invoice->pay_date?->format($global->date_format) }} @if($invoice->next_pay_date) - {{ $invoice->next_pay_date->format($global->date_format) }} @endif</td>
                        <td>@if(!is_null($invoice->currency)){!! htmlentities($invoice->currency->currency_code)  !!}@else ₹ @endif{{ number_format((float)$invoice->total, 2, '.', '') }}</td>
                    </tr>

                </table>

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td colspan="3">@lang('modules.invoices.subTotal'):</td>
                        <td>@if(!is_null($invoice->currency)){!! htmlentities($invoice->currency->currency_code)  !!}@else ₹ @endif{{ number_format((float)$invoice->total, 2, '.', '') }}</td>
                    </tr>
                    <tr class="amount-total">
                        <td colspan="3">
                            @lang('modules.invoices.total'):
                        </td>
                        <td>
                            @if(!is_null($invoice->currency)){!! htmlentities($invoice->currency->currency_code)  !!}@else ₹ @endif{{ number_format((float)$invoice->total, 2, '.', '') }}
                        </td>
                    </tr>
                </table>

            </section>
            @if ($globalInvoiceSetting->authorised_signatory && $globalInvoiceSetting->authorised_signatory_signature)
            <section id="terms">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%" style="">
                                <tr>
                                    <td style="font-size:15px; text-align: right">
                                        <img style="height:95px; margin-bottom: -50px; margin-top: 15px;"
                                        src="{{ $globalInvoiceSetting->authorised_signatory_signature_url }}" alt="{{ $global->company_name }}"/><br><br>
                                        <p style="margin-top: 25px;">@lang('modules.invoiceSettings.authorisedSignatory')</p>
                                    </td>
                                </tr>
                            </table>
                </section>
                @endif
            <section id="terms">
                <div class="word-break item-summary description">@lang('modules.invoiceSettings.invoiceTerms') <br>{!! nl2br($globalInvoiceSetting->invoice_terms) !!}</div>

            </section>

        </div>

    </div>

</body>

</html>
