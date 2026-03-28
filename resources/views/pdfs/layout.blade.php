<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <style>
    @page {
      margin: 40px 45px;
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #111;
    }

    .row {
      width: 100%;
    }

    .left {
      float: left;
    }

    .right {
      float: right;
    }

    .clearfix::after {
      content: "";
      display: table;
      clear: both;
    }

    .muted {
      color: #444;
    }

    .divider {
      border-top: 2px solid #111;
      margin: 14px 0 28px;
    }

    .header-left {
      width: 52%;
    }

    .header-right {
      width: 46%;
      text-align: right;
    }

    .title {
      text-align: center;
      font-weight: 700;
      letter-spacing: 2px;
      margin: 18px 0 34px;
      font-size: 16px;
    }

    .date-right {
      text-align: right;
      margin-top: 4px;
      font-size: 14px;
    }

    .signature {
      margin-top: 120px;
      width: 100%;
    }

    .sig-right {
      width: 55%;
      float: right;
      text-align: left;
    }

    .sig-name {
      font-weight: 700;
      letter-spacing: 1px;
    }
  </style>
  @yield("styles")
</head>

<body>
  @include("pdfs.partials.clinic-header")
  @yield("content")
</body>

</html>
