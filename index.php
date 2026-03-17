<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>班級管理系統</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:        #f0f4f8;
      --surface:   #ffffff;
      --primary:   #2563eb;
      --primary-h: #1d4ed8;
      --accent:    #0ea5e9;
      --success:   #16a34a;
      --danger:    #dc2626;
      --text:      #1e293b;
      --muted:     #64748b;
      --border:    #e2e8f0;
      --radius:    12px;
      --shadow:    0 4px 24px rgba(0,0,0,.08);
    }

    body {
      font-family: 'Segoe UI', 'Microsoft JhengHei', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      padding: 32px 16px;
    }

    header {
      text-align: center;
      margin-bottom: 40px;
    }
    header h1 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
      letter-spacing: .5px;
    }
    header p { color: var(--muted); margin-top: 6px; font-size: .95rem; }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
      gap: 28px;
      max-width: 900px;
      margin: 0 auto;
    }

    .card {
      background: var(--surface);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 28px 24px;
      border-top: 4px solid var(--primary);
    }
    .card:nth-child(2) { border-top-color: var(--accent); }

    .card h2 {
      font-size: 1.15rem;
      font-weight: 700;
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .badge {
      font-size: .65rem;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 99px;
      text-transform: uppercase;
      letter-spacing: .5px;
    }
    .badge-get  { background: #dbeafe; color: var(--primary); }
    .badge-post { background: #e0f2fe; color: #0369a1; }

    .card p.desc { color: var(--muted); font-size: .875rem; margin-bottom: 20px; }

    label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: 6px; }

    input[type="text"] {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-size: .95rem;
      outline: none;
      transition: border-color .2s;
    }
    input[type="text"]:focus { border-color: var(--primary); }

    button {
      margin-top: 14px;
      width: 100%;
      padding: 11px;
      border: none;
      border-radius: 8px;
      font-size: .95rem;
      font-weight: 600;
      cursor: pointer;
      transition: background .2s, transform .1s;
    }
    button:active { transform: scale(.98); }
    .btn-get  { background: var(--primary);   color: #fff; }
    .btn-get:hover  { background: var(--primary-h); }
    .btn-post { background: var(--accent);    color: #fff; }
    .btn-post:hover { background: #0284c7; }

    .result {
      margin-top: 20px;
      border-radius: 8px;
      overflow: hidden;
      border: 1.5px solid var(--border);
      display: none;
    }
    .result.show { display: block; }
    .result-header {
      padding: 10px 14px;
      font-size: .8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .5px;
      background: var(--bg);
      color: var(--muted);
    }
    .result-body { padding: 16px; }

    /* Teacher result */
    .info-row { display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 1px solid var(--border); font-size: .9rem; }
    .info-row:last-child { border-bottom: none; }
    .info-row span:first-child { color: var(--muted); }
    .info-row span:last-child  { font-weight: 600; }

    /* Students table */
    table { width: 100%; border-collapse: collapse; font-size: .85rem; }
    th, td { padding: 8px 10px; text-align: center; border-bottom: 1px solid var(--border); }
    th { background: var(--bg); font-weight: 700; color: var(--muted); font-size: .78rem; text-transform: uppercase; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }
    .score-high { color: var(--success); font-weight: 700; }
    .score-low  { color: var(--danger);  font-weight: 700; }

    .error-msg { color: var(--danger); font-size: .875rem; padding: 10px 0; }
    .loading   { color: var(--muted);  font-size: .875rem; padding: 10px 0; text-align: center; }
  </style>
</head>
<body>

<header>
  <h1>📚 班級管理系統</h1>
  <p>輸入班級編號查詢導師資訊或班級同學成績</p>
</header>

<div class="grid">

  <!-- ── 功能一：查詢導師 (GET) ── -->
  <div class="card">
    <h2>查詢班級導師 <span class="badge badge-get">GET</span></h2>
    <p class="desc">輸入班級編號，透過 GET 請求查詢該班的班級導師。</p>

    <label for="getClassId">班級編號</label>
    <input type="text" id="getClassId" placeholder="例如：C001">
    <button class="btn-get" onclick="queryTeacher()">查詢導師</button>

    <div class="result" id="teacherResult">
      <div class="result-header">查詢結果</div>
      <div class="result-body" id="teacherBody"></div>
    </div>
  </div>

  <!-- ── 功能二：查詢同學 (POST) ── -->
  <div class="card">
    <h2>查詢班級同學 <span class="badge badge-post">POST</span></h2>
    <p class="desc">輸入班級編號，透過 POST 請求查詢該班所有同學及成績。</p>

    <label for="postClassId">班級編號</label>
    <input type="text" id="postClassId" placeholder="例如：C001">
    <button class="btn-post" onclick="queryStudents()">查詢同學</button>

    <div class="result" id="studentsResult">
      <div class="result-header">查詢結果</div>
      <div class="result-body" id="studentsBody"></div>
    </div>
  </div>

</div>

<!-- 引入 jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
  $(document).ready(function () {

    /* ── 功能一：GET 查詢導師 ── */
    function queryTeacher() {
      var classId = $('#getClassId').val().trim();

      if (!classId) { alert('請輸入班級編號'); return; }

      $('#teacherResult').addClass('show');
      $('#teacherBody').html('<p class="loading">查詢中…</p>');

      $.ajax({
        url      : 'get_teacher.php',
        type     : 'GET',
        data     : { '班級編號': classId },
        dataType : 'json',
        success  : function (json) {
          if (json.success) {
            var d = json.data;
            $('#teacherBody').html(
              '<div class="info-row"><span>班級編號</span><span>' + d['班級編號'] + '</span></div>' +
              '<div class="info-row"><span>班級名稱</span><span>' + d['班級名稱'] + '</span></div>' +
              '<div class="info-row"><span>班級導師</span><span>' + d['班級導師'] + '</span></div>'
            );
          } else {
            $('#teacherBody').html('<p class="error-msg">⚠️ ' + json.message + '</p>');
          }
        },
        error    : function () {
          $('#teacherBody').html('<p class="error-msg">⚠️ 請求失敗，請確認伺服器狀態</p>');
        }
      });
    }

    /* ── 功能二：POST 查詢同學 ── */
    function queryStudents() {
      var classId = $('#postClassId').val().trim();

      if (!classId) { alert('請輸入班級編號'); return; }

      $('#studentsResult').addClass('show');
      $('#studentsBody').html('<p class="loading">查詢中…</p>');

      $.ajax({
        url      : 'post_students.php',
        type     : 'POST',
        data     : { '班級編號': classId },
        dataType : 'json',
        success  : function (json) {
          if (json.success) {
            var rows = '';
            $.each(json.data, function (i, s) {
              var avg = s['平均成績'];
              var cls = avg >= 80 ? 'score-high' : (avg < 60 ? 'score-low' : '');
              rows +=
                '<tr>' +
                  '<td>' + s['同學編號'] + '</td>' +
                  '<td>' + s['同學姓名'] + '</td>' +
                  '<td>' + s['英文成績'] + '</td>' +
                  '<td>' + s['數學成績'] + '</td>' +
                  '<td>' + s['國文成績'] + '</td>' +
                  '<td class="' + cls + '">' + s['平均成績'] + '</td>' +
                '</tr>';
            });

            $('#studentsBody').html(
              '<p style="font-size:.85rem;color:var(--muted);margin-bottom:10px;">' +
                json['班級名稱'] + ' &nbsp;｜&nbsp; 共 ' + json['學生人數'] + ' 位同學' +
              '</p>' +
              '<table>' +
                '<thead><tr><th>編號</th><th>姓名</th><th>英文</th><th>數學</th><th>國文</th><th>平均</th></tr></thead>' +
                '<tbody>' + rows + '</tbody>' +
              '</table>'
            );
          } else {
            $('#studentsBody').html('<p class="error-msg">⚠️ ' + json.message + '</p>');
          }
        },
        error    : function () {
          $('#studentsBody').html('<p class="error-msg">⚠️ 請求失敗，請確認伺服器狀態</p>');
        }
      });
    }

    /* ── 按鈕點擊事件 ── */
    $('.btn-get').on('click', queryTeacher);
    $('.btn-post').on('click', queryStudents);

    /* ── Enter 鍵觸發查詢 ── */
    $('#getClassId').on('keydown', function (e) {
      if (e.key === 'Enter') queryTeacher();
    });
    $('#postClassId').on('keydown', function (e) {
      if (e.key === 'Enter') queryStudents();
    });

  });
</script>

</body>
</html>