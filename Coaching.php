<?php
// Database configuration
$db_host = "localhost";
$db_name = "decoder_classes";
$db_user = "root";
$db_pass = "";

// Form status
$form_success = false;
$form_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $is_ajax = strtolower($_SERVER["HTTP_X_REQUESTED_WITH"] ?? "") === "xmlhttprequest";
    $student_name = trim($_POST["student"] ?? "");
    $parent_name = trim($_POST["parent"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $class_name = trim($_POST["class"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $batch = trim($_POST["batch"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // Server-side validation
    if ($student_name === "" || $parent_name === "" || $mobile === "" || $class_name === "") {
        $form_error = "Please fill in all required fields.";
    } elseif (!preg_match('/^[6-9][0-9]{9}$/', $mobile)) {
        $form_error = "Please enter a valid 10-digit mobile number.";
    } else {
        try {
            $pdo = new PDO(
                "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
                $db_user,
                $db_pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

            $sql = "INSERT INTO enquiries
                    (student_name, parent_name, mobile, class_name, course, preferred_batch, message)
                    VALUES (:student_name, :parent_name, :mobile, :class_name, :course, :preferred_batch, :message)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':student_name' => $student_name,
                ':parent_name' => $parent_name,
                ':mobile' => $mobile,
                ':class_name' => $class_name,
                ':course' => $course,
                ':preferred_batch' => $batch,
                ':message' => $message
            ]);

            $form_success = true;
        } catch (PDOException $e) {
            // Do not expose database details to website visitors.
            $form_error = "We could not submit your enquiry right now. Please try again.";
        }
    }

    // AJAX submissions receive a small response instead of re-rendering the page.
    if ($is_ajax) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($form_error === "" ? 200 : 422);
        echo json_encode([
            'success' => $form_success,
            'message' => $form_success
                ? 'Enquiry received successfully. Our team will call you within one working day.'
                : $form_error
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Decoder Classes — Decode Your Potential. Build Your Future.</title>
<meta name="description" content="Concept-focused coaching for Class 8–12 with regular tests, doubt solving and personal attention.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

</head>
<body>

<!-- SVG sprite -->
<svg width="0" height="0" class="svg-sprite" aria-hidden="true">
  <defs>
    <symbol id="i-check" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M5 12.5l4.2 4.2L19 7"/></symbol>
    <symbol id="i-circle-check" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.5"/><path d="M8 12.5l2.7 2.7L16 9.5"/></g></symbol>
    <symbol id="i-bulb" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M9 18h6M10 21h4M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2.1h5c0-.9.4-1.6 1-2.1A6 6 0 0 0 12 3z"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/></g></symbol>
    <symbol id="i-clip" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4h6v3H9zM9 14l2 2 4-4"/></g></symbol>
    <symbol id="i-chat" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8 8 0 0 1-11.5 7.2L4 20l1-4.5A8 8 0 1 1 21 12z"/><path d="M9.7 10a2.4 2.4 0 1 1 3.4 2.2c-.6.3-1.1.8-1.1 1.5M12 16.8h.01"/></g></symbol>
    <symbol id="i-book" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5zM4 19.5A2.5 2.5 0 0 0 6.5 22H20v-5M9 8h6"/></symbol>
    <symbol id="i-trend" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8M15 7h6v6"/></symbol>
    <symbol id="i-cap" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M2 9l10-5 10 5-10 5zM6 11.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5M22 9v6"/></symbol>
    <symbol id="i-award" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6"/><path d="M8.5 14L7 22l5-3 5 3-1.5-8"/></g></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.6"/></g></symbol>
    <symbol id="i-phone" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M3.5 7l8.5 6 8.5-6"/></g></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.5"/><path d="M12 7v5l3.5 2"/></g></symbol>
    <symbol id="i-lock" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2.5"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></g></symbol>
    <symbol id="i-plus" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" d="M12 5v14M5 12h14"/></symbol>
    <symbol id="i-arrow-l" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 5l-7 7 7 7"/></symbol>
    <symbol id="i-arrow-r" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></symbol>
    <symbol id="i-test" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h9l4 4v14H6z"/><path d="M14 3v5h5M9 13h7M9 17h5"/></g></symbol>
    <symbol id="i-people" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6M16 4.8a3.5 3.5 0 0 1 0 6.4M18 14.4c2 .7 3.5 2.6 3.5 5.6"/></g></symbol>
    <symbol id="i-mic" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="3" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3"/></g></symbol>
    <symbol id="i-ig" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.3 6.8h.01"/></g></symbol>
    <symbol id="i-fb" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/></symbol>
    <symbol id="i-yt" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="M10 9.3v5.4l4.6-2.7z"/></g></symbol>
    <symbol id="i-wa" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12a8 8 0 0 1-11.9 7L4 20l1.1-4A8 8 0 1 1 20 12z"/><path d="M9 8.5c0 3.5 3 6.5 6.5 6.5l1-1.6-2-1-1 .8c-.9-.4-1.8-1.3-2.2-2.2l.8-1-1-2z"/></g></symbol>
    <symbol id="i-person" viewBox="0 0 200 180"><path fill="currentColor" d="M100 14c-22 0-38 17-38 40 0 20 11 36 26 42-34 8-62 30-68 84h160c-6-54-34-76-68-84 15-6 26-22 26-42 0-23-16-40-38-40z"/></symbol>
    <symbol id="i-pin-solid" viewBox="0 0 24 24"><path fill="currentColor" d="M12 22s7.5-6.6 7.5-12.3A7.5 7.5 0 0 0 4.5 9.7C4.5 15.4 12 22 12 22z"/><circle cx="12" cy="9.7" r="3" fill="#fff"/></symbol>
    <symbol id="i-logo" viewBox="0 0 44 44"><rect width="44" height="44" rx="12" fill="#2450F5"/><path d="M14 11h8a11 11 0 0 1 0 22h-8z" fill="none" stroke="#fff" stroke-width="3.4" stroke-linejoin="round"/><path d="M21 17.5l5 4.5-5 4.5" fill="none" stroke="#22D3EE" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></symbol>
  </defs>
</svg>

<!-- NAV -->
<header class="nav" id="nav">
  <div class="wrap nav-in">
    <a href="#home" class="logo" aria-label="Decoder Classes home">
      <svg width="44" height="44"><use href="#i-logo"/></svg>
      <span><b>DECODER</b><small>CLASSES</small></span>
    </a>
    <ul class="menu" id="menu">
      <li><a href="#home">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#courses">Courses</a></li>
      <li><a href="#results">Results</a></li>
      <li><a href="#faculty">Faculty</a></li>
      <li><a href="#gallery">Gallery</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-r">
      <a href="#enquiry" class="btn btn-primary btn-sm">Enquire Now</a>
      <button class="burger" id="burger" aria-label="Open menu" aria-expanded="false" aria-controls="menu"><span></span></button>
    </div>
  </div>
</header>

<main id="home">
<!-- HERO -->
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <span class="badge"><i></i>Trusted Learning • Better Results</span>
      <h1>Decode Your <span class="hl" id="decode">Potential</span>.<span class="fut">Build Your Future.</span></h1>
      <p class="lead">Concept-focused learning, personal attention and consistent guidance to help students achieve their academic goals.</p>
      <div class="cta-row">
        <a href="#enquiry" class="btn btn-primary">Book Free Counselling <span class="arr">→</span></a>
        <a href="#courses" class="btn btn-ghost">Explore Courses</a>
      </div>
      <ul class="trust">
        <li><svg><use href="#i-circle-check"/></svg>Experienced Faculty</li>
        <li><svg><use href="#i-circle-check"/></svg>Regular Tests</li>
        <li><svg><use href="#i-circle-check"/></svg>Personal Attention</li>
      </ul>
    </div>

    <div class="hv" aria-hidden="true">
      <span class="formula formula-1">∫ x² dx = x³/3 + C</span>
      <span class="formula formula-2">E = mc²</span>
      <span class="formula formula-3">sin²θ + cos²θ = 1</span>
      <div class="board">
        <div class="board-h">
          <div><strong>Mathematics · Unit Test 6</strong><br><span>Class 10 · Batch A</span></div>
          <span class="pill">+14% vs last test</span>
        </div>
        <div class="bars"><div class="bar bar-1"></div><div class="bar bar-2"></div><div class="bar bar-3"></div><div class="bar bar-4"></div><div class="bar bar-5"></div><div class="bar bar-6"></div><div class="bar bar-7"></div></div>
        <div class="axis"><span>T1</span><span>T2</span><span>T3</span><span>T4</span><span>T5</span><span>T6</span><span>T7</span></div>
        <div class="topics">
          <div class="topic">Algebra<div class="meter"><i class="meter-92"></i></div><em>92%</em></div>
          <div class="topic">Geometry<div class="meter"><i class="meter-84"></i></div><em>84%</em></div>
          <div class="topic">Trigonometry<div class="meter"><i class="meter-76"></i></div><em>76%</em></div>
        </div>
      </div>
      <div class="notebook">
        <b>x</b> = (−b ± √(b²−4ac))/2a<br>
        <b>F</b> = m · a<br>
        a² + b² = c²<br>
        ∴ concept first, <b>then</b> speed
      </div>
      <div class="hv-fc">
        <div class="fc fc1"><div class="ic"><svg><use href="#i-award"/></svg></div><div><strong>95%+</strong><span>Student Satisfaction</span></div></div>
        <div class="fc fc2"><div class="ic"><svg><use href="#i-people"/></svg></div><div><strong>1000+</strong><span>Students Guided</span></div></div>
        <div class="fc fc3"><div class="ic"><svg><use href="#i-test"/></svg></div><div><strong class="fc-regular">Regular</strong><span>Tests &amp; Assessments</span></div></div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="strip" aria-label="Key numbers">
  <div class="wrap">
    <div class="strip-in">
      <div class="stat"><strong data-count="1000" data-suffix="+">1000+</strong><span>Students</span></div>
      <div class="stat"><strong data-count="10" data-suffix="+">10+</strong><span>Years of Teaching</span></div>
      <div class="stat"><strong data-count="95" data-suffix="%+">95%+</strong><span>Student Satisfaction</span></div>
      <div class="stat"><strong class="gold" data-count="50" data-suffix="+">50+</strong><span>Top Results</span></div>
    </div>
  </div>
</section>

<!-- WHY -->
<section class="sec" id="about">
  <div class="wrap">
    <div class="sec-head">
      <h2>Why Students Choose Decoder Classes</h2>
      <p>More than teaching — we focus on understanding.</p>
    </div>
    <div class="why-grid">
      <article class="card"><div class="ico"><svg><use href="#i-bulb"/></svg></div><h3>Concept-Based Learning</h3><p>Understand the “why”, not just the answer.</p></article>
      <article class="card"><div class="ico"><svg><use href="#i-user"/></svg></div><h3>Personal Attention</h3><p>Individual guidance according to student needs.</p></article>
      <article class="card"><div class="ico"><svg><use href="#i-clip"/></svg></div><h3>Regular Assessments</h3><p>Frequent tests to measure actual progress.</p></article>
      <article class="card"><div class="ico"><svg><use href="#i-chat"/></svg></div><h3>Doubt Solving</h3><p>Dedicated doubt-solving support.</p></article>
      <article class="card"><div class="ico"><svg><use href="#i-book"/></svg></div><h3>Study Material</h3><p>Structured notes, practice material and revision resources.</p></article>
      <article class="card"><div class="ico"><svg><use href="#i-trend"/></svg></div><h3>Performance Tracking</h3><p>Track student improvement and identify weak areas.</p></article>
    </div>
  </div>
</section>

<!-- COURSES -->
<section class="sec courses" id="courses">
  <div class="wrap">
    <div class="sec-head center">
      <h2>Courses Designed for Every Stage</h2>
      <p>Pick the programme that matches where your child is today.</p>
    </div>
    <div class="course-grid">
      <article class="course">
        <span class="cls">Class 8–9</span>
        <h3>Foundation Program</h3>
        <div class="sub">Subjects</div>
        <div class="chips"><span>Mathematics</span><span>Science</span></div>
        <div class="sub">Features</div>
        <ul><li><svg><use href="#i-check"/></svg>Concept Building</li><li><svg><use href="#i-check"/></svg>Regular Tests</li></ul>
        <a href="#enquiry" class="btn btn-outline">View Details <span class="arr">→</span></a>
      </article>
      <article class="course feat">
        <span class="tag">Most Popular</span>
        <span class="cls">Class 10</span>
        <h3>Board Excellence Program</h3>
        <div class="sub">Subjects</div>
        <div class="chips"><span>Mathematics</span><span>Science</span></div>
        <div class="sub">Features</div>
        <ul><li><svg><use href="#i-check"/></svg>Board Preparation</li><li><svg><use href="#i-check"/></svg>Mock Tests</li></ul>
        <a href="#enquiry" class="btn btn-primary">View Details <span class="arr">→</span></a>
      </article>
      <article class="course">
        <span class="cls">Class 11–12</span>
        <h3>Advanced Academic Program</h3>
        <div class="sub">Subjects</div>
        <div class="chips"><span>PCM</span><span>PCB</span></div>
        <div class="sub">Features</div>
        <ul><li><svg><use href="#i-check"/></svg>Conceptual Learning</li><li><svg><use href="#i-check"/></svg>Competitive Preparation</li><li><svg><use href="#i-check"/></svg>Regular Assessments</li></ul>
        <a href="#enquiry" class="btn btn-outline">View Details <span class="arr">→</span></a>
      </article>
    </div>
  </div>
</section>

<!-- RESULTS -->
<section class="sec dark" id="results">
  <div class="wrap">
    <div class="sec-head">
      <h2>Results That Speak For Themselves.</h2>
      <p>Consistent practice and personal guidance, reflected in the scores.</p>
    </div>
    <div class="big3">
      <div class="big"><strong data-count="98.6" data-dec="1" data-suffix="%">98.6%</strong><span>Highest Score</span></div>
      <div class="big"><strong data-count="95" data-suffix="%+">95%+</strong><span>Students Above Target</span></div>
      <div class="big"><strong data-count="100" data-suffix="+">100+</strong><span>Distinction Students</span></div>
    </div>
    <div class="res-grid">
      <div class="res"><div class="av">AP</div><div><h4>Aarav Patel</h4><small>Class 10 · Board Examination · 2025</small></div><div class="score">96.8%</div></div>
      <div class="res"><div class="av">RS</div><div><h4>Riya Shah</h4><small>Class 12 · Physics · 2025</small></div><div class="score">94.2%</div></div>
      <div class="res"><div class="av">KM</div><div><h4>Karan Mehta</h4><small>Class 12 · Mathematics · 2025</small></div><div class="score">98.0%</div></div>
      <div class="res"><div class="av">AJ</div><div><h4>Anaya Joshi</h4><small>Class 10 · Science · 2025</small></div><div class="score">95.4%</div></div>
      <div class="res"><div class="av">DD</div><div><h4>Dev Desai</h4><small>Class 9 · Mathematics · 2025</small></div><div class="score">97.0%</div></div>
      <div class="res"><div class="av">IN</div><div><h4>Ishita Naik</h4><small>Class 11 · Chemistry · 2025</small></div><div class="score">93.6%</div></div>
    </div>
    <p class="note">Sample data shown as UI placeholders. Replace with verified student results before launch.</p>
  </div>
</section>

<!-- FACULTY -->
<section class="sec" id="faculty">
  <div class="wrap">
    <div class="sec-head">
      <h2>Learn From Experienced Mentors</h2>
      <p>Teachers who explain patiently and track every student's progress.</p>
    </div>
    <div class="fac-grid">
      <article class="fac"><div class="photo"><em>Photo</em><svg viewBox="0 0 200 180"><use href="#i-person"/></svg></div><div class="fac-b"><h3>Rahul Sharma</h3><div class="subj">Mathematics Faculty</div><p><svg><use href="#i-cap"/></svg>M.Sc. Mathematics</p><p><svg><use href="#i-clock"/></svg>8+ Years Experience</p></div></article>
      <article class="fac"><div class="photo"><em>Photo</em><svg viewBox="0 0 200 180"><use href="#i-person"/></svg></div><div class="fac-b"><h3>Neha Verma</h3><div class="subj">Physics Faculty</div><p><svg><use href="#i-cap"/></svg>M.Sc. Physics, B.Ed.</p><p><svg><use href="#i-clock"/></svg>7+ Years Experience</p></div></article>
      <article class="fac"><div class="photo"><em>Photo</em><svg viewBox="0 0 200 180"><use href="#i-person"/></svg></div><div class="fac-b"><h3>Amit Desai</h3><div class="subj">Chemistry Faculty</div><p><svg><use href="#i-cap"/></svg>M.Sc. Chemistry</p><p><svg><use href="#i-clock"/></svg>10+ Years Experience</p></div></article>
      <article class="fac"><div class="photo"><em>Photo</em><svg viewBox="0 0 200 180"><use href="#i-person"/></svg></div><div class="fac-b"><h3>Priya Nair</h3><div class="subj">Science &amp; Biology Faculty</div><p><svg><use href="#i-cap"/></svg>M.Sc. Life Sciences</p><p><svg><use href="#i-clock"/></svg>6+ Years Experience</p></div></article>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="sec testi">
  <div class="wrap">
    <div class="testi-top">
      <div class="sec-head"><h2>What Our Students Say</h2></div>
      <div class="arrows"><button id="prev" aria-label="Previous testimonials"><svg><use href="#i-arrow-l"/></svg></button><button id="next" aria-label="Next testimonials"><svg><use href="#i-arrow-r"/></svg></button></div>
    </div>
    <div class="track" id="track" tabindex="0" aria-label="Student testimonials">
      <article class="tcard"><div class="stars" aria-label="5 out of 5 stars">★★★★★</div><blockquote>“Decoder Classes helped me understand concepts that I previously struggled with. The regular tests and doubt sessions made a big difference.”</blockquote><div class="who"><div class="av">AP</div><div><strong>Aarav Patel</strong><span>Class 10</span></div></div></article>
      <article class="tcard"><div class="stars" aria-label="5 out of 5 stars">★★★★★</div><blockquote>“Every test came back with notes on exactly what to fix. I stopped guessing what to study.”</blockquote><div class="who"><div class="av">RS</div><div><strong>Riya Shah</strong><span>Class 12</span></div></div></article>
      <article class="tcard"><div class="stars" aria-label="5 out of 5 stars">★★★★★</div><blockquote>“Physics finally makes sense. Teachers never rush and always ask us to explain the reason.”</blockquote><div class="who"><div class="av">KM</div><div><strong>Karan Mehta</strong><span>Class 12</span></div></div></article>
      <article class="tcard"><div class="stars" aria-label="5 out of 5 stars">★★★★★</div><blockquote>“Small batches mean doubts get answered the same day. My confidence in maths went up a lot.”</blockquote><div class="who"><div class="av">AJ</div><div><strong>Anaya Joshi</strong><span>Class 9</span></div></div></article>
    </div>
  </div>
</section>

<!-- GALLERY -->
<section class="sec" id="gallery">
  <div class="wrap">
    <div class="sec-head"><h2>Life at Decoder Classes</h2><p>Classrooms, seminars, tests and celebrations.</p></div>
    <div class="filters" id="filters" role="group" aria-label="Filter gallery">
      <button class="on" data-f="all">All</button><button data-f="classroom">Classroom</button><button data-f="events">Events</button><button data-f="seminars">Seminars</button><button data-f="tests">Tests</button><button data-f="achievements">Achievements</button>
    </div>
    <div class="gal" id="gal">
      <figure class="g t w" data-c="classroom"><div class="bg p1"><svg class="pat" viewBox="0 0 400 400" preserveAspectRatio="xMidYMid slice"><g fill="none" stroke="#fff" stroke-opacity=".35"><path d="M0 300h400M0 340h400M60 0v400M120 0v400"/><circle cx="270" cy="140" r="70"/><path d="M200 140h140M270 70v140"/></g></svg></div><div class="glyph"><svg><use href="#i-cap"/></svg></div><figcaption class="cap"><b>Interactive classroom</b><span>Classroom</span></figcaption></figure>
      <figure class="g" data-c="tests"><div class="bg p2"><svg class="pat" viewBox="0 0 200 200"><g fill="none" stroke="#fff" stroke-opacity=".35"><rect x="50" y="30" width="100" height="140" rx="8"/><path d="M70 70h60M70 95h60M70 120h40"/></g></svg></div><div class="glyph"><svg><use href="#i-test"/></svg></div><figcaption class="cap"><b>Weekly test</b><span>Tests</span></figcaption></figure>
      <figure class="g t" data-c="achievements"><div class="bg p4"><svg class="pat" viewBox="0 0 200 400" preserveAspectRatio="xMidYMid slice"><g fill="none" stroke="#fff" stroke-opacity=".3"><circle cx="100" cy="150" r="50"/><circle cx="100" cy="150" r="80"/><path d="M70 330l30-60 30 60"/></g></svg></div><div class="glyph"><svg><use href="#i-award"/></svg></div><figcaption class="cap"><b>Toppers felicitation</b><span>Achievements</span></figcaption></figure>
      <figure class="g" data-c="seminars"><div class="bg p3"><svg class="pat" viewBox="0 0 200 200"><g fill="none" stroke="#fff" stroke-opacity=".35"><path d="M20 160h160M40 160V90M80 160V60M120 160V110M160 160V40"/></g></svg></div><div class="glyph"><svg><use href="#i-mic"/></svg></div><figcaption class="cap"><b>Career seminar</b><span>Seminars</span></figcaption></figure>
      <figure class="g" data-c="events"><div class="bg p5"><svg class="pat" viewBox="0 0 200 200"><g fill="none" stroke="#fff" stroke-opacity=".4"><circle cx="60" cy="60" r="30"/><circle cx="140" cy="140" r="40"/><path d="M60 60L140 140"/></g></svg></div><div class="glyph"><svg><use href="#i-people"/></svg></div><figcaption class="cap"><b>Annual day</b><span>Events</span></figcaption></figure>
      <figure class="g w" data-c="classroom"><div class="bg p2"><svg class="pat" viewBox="0 0 400 200" preserveAspectRatio="xMidYMid slice"><g fill="none" stroke="#fff" stroke-opacity=".3"><path d="M20 40h140M20 80h100M20 120h120"/><path d="M230 160c40-120 100-120 140 0"/><path d="M230 160h140"/></g></svg></div><div class="glyph"><svg><use href="#i-book"/></svg></div><figcaption class="cap"><b>Doubt-solving session</b><span>Classroom</span></figcaption></figure>
      <figure class="g" data-c="tests"><div class="bg p1"><svg class="pat" viewBox="0 0 200 200"><g fill="none" stroke="#fff" stroke-opacity=".35"><path d="M40 150l40-50 30 30 50-70"/><path d="M30 170h140"/></g></svg></div><div class="glyph"><svg><use href="#i-trend"/></svg></div><figcaption class="cap"><b>Mock exam day</b><span>Tests</span></figcaption></figure>
      <figure class="g" data-c="achievements"><div class="bg p3"><svg class="pat" viewBox="0 0 200 200"><g fill="none" stroke="#fff" stroke-opacity=".35"><circle cx="100" cy="90" r="40"/><path d="M80 130l-10 40 30-18 30 18-10-40"/></g></svg></div><div class="glyph"><svg><use href="#i-award"/></svg></div><figcaption class="cap"><b>Distinction awards</b><span>Achievements</span></figcaption></figure>
    </div>
    <div class="gal-foot"><a href="#gallery" class="btn btn-outline">View Gallery <span class="arr">→</span></a></div>
    <p class="note note-centered">Placeholder tiles. Replace with real classroom and event photography.</p>
  </div>
</section>

<!-- CTA BANNER -->
<section class="cta">
  <div class="wrap">
    <div class="cta-box">
      <span class="ring r1"></span><span class="ring r2"></span>
      <h2>Ready to Decode Your Potential?</h2>
      <p>Start your academic journey with Decoder Classes.</p>
      <div class="cta-row">
        <a href="#enquiry" class="btn btn-white">Book Free Counselling</a>
        <a href="https://wa.me/910000000000" class="btn btn-wa"><svg width="20" height="20"><use href="#i-wa"/></svg>Talk to Us on WhatsApp</a>
      </div>
    </div>
  </div>
</section>

<!-- ENQUIRY -->
<section class="sec enq" id="enquiry">
  <div class="wrap enq-grid">
    <div class="enq-side">
      <h2>Book Your Free Counselling Session</h2>
      <p>Talk to a senior mentor about your child's goals, current level and the right batch.</p>
      <ol class="steps">
        <li><strong>Send your details</strong><span>Takes under a minute.</span></li>
        <li><strong>We call you back</strong><span>Within one working day.</span></li>
        <li><strong>Visit and attend a demo class</strong><span>See how we teach before you decide.</span></li>
      </ol>
    </div>
    <form class="form" id="form" method="POST" action="" novalidate>
      <h3>Enquiry form</h3>
      <?php if ($form_success): ?>
        <div class="server-message success-message" role="status">
          Enquiry received successfully. Our team will call you within one working day.
        </div>
      <?php elseif ($form_error !== ""): ?>
        <div class="server-message error-message" role="alert">
          <?= htmlspecialchars($form_error, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>
      <div class="fgrid">
        <div class="fld"><label for="sn">Student Name</label><input id="sn" name="student" required autocomplete="off" placeholder="e.g. Aarav Patel"><span class="err">Enter the student's name.</span></div>
        <div class="fld"><label for="pn">Parent Name</label><input id="pn" name="parent" required autocomplete="off" placeholder="e.g. Mrs. Patel"><span class="err">Enter the parent's name.</span></div>
        <div class="fld"><label for="mn">Mobile Number</label><input id="mn" name="mobile" type="tel" inputmode="numeric" required maxlength="10" placeholder="10-digit mobile number"><span class="err">Enter a valid 10-digit mobile number.</span></div>
        <div class="fld"><label for="cl">Class</label><select id="cl" name="class" required><option value="">Select class</option><option>Class 8</option><option>Class 9</option><option>Class 10</option><option>Class 11</option><option>Class 12</option></select><span class="err">Select a class.</span></div>
        <div class="fld"><label for="co">Subject / Course</label><select id="co" name="course"><option value="">Select course</option><option>Foundation Program</option><option>Board Excellence Program</option><option>Advanced Academic Program (PCM)</option><option>Advanced Academic Program (PCB)</option></select></div>
        <div class="fld"><label for="ba">Preferred Batch</label><select id="ba" name="batch"><option value="">Select batch</option><option>Morning</option><option>Afternoon</option><option>Evening</option></select></div>
        <div class="fld full"><label for="ms">Message</label><textarea id="ms" name="message" placeholder="Anything we should know? (optional)"></textarea></div>
      </div>
      <button class="btn btn-primary" type="submit">Submit Enquiry <span class="arr">→</span></button>
      <p class="fine"><svg><use href="#i-lock"/></svg>No spam. Your information is only used to contact you regarding admission.</p>
      <div class="ok" role="status"><div class="tick"><svg><use href="#i-check"/></svg></div><h3>Enquiry received</h3><p>Thank you. Our team will call you within one working day.</p></div>
    </form>
  </div>
</section>

<!-- FAQ -->
<section class="sec">
  <div class="wrap faq-grid">
    <div class="sec-head"><h2>Questions parents ask</h2><p>Can't find your answer? Call us or message on WhatsApp.</p></div>
    <div class="acc" id="acc">
      <div class="qa open"><button aria-expanded="true"><span>Which classes do you teach?</span><i class="pm"><svg><use href="#i-plus"/></svg></i></button><div class="ans"><div><p>We teach Class 8 to Class 12, with separate programmes for foundation, board preparation and advanced academics.</p></div></div></div>
      <div class="qa"><button aria-expanded="false"><span>Which subjects are available?</span><i class="pm"><svg><use href="#i-plus"/></svg></i></button><div class="ans"><div><p>Mathematics and Science for Class 8–10. Physics, Chemistry, Mathematics and Biology for Class 11–12.</p></div></div></div>
      <div class="qa"><button aria-expanded="false"><span>Do you provide demo classes?</span><i class="pm"><svg><use href="#i-plus"/></svg></i></button><div class="ans"><div><p>Yes. Students can attend a free demo class before joining. Book one through the enquiry form.</p></div></div></div>
      <div class="qa"><button aria-expanded="false"><span>Are regular tests conducted?</span><i class="pm"><svg><use href="#i-plus"/></svg></i></button><div class="ans"><div><p>Yes. Weekly unit tests and periodic mock exams, with a progress report shared with parents.</p></div></div></div>
      <div class="qa"><button aria-expanded="false"><span>Do you provide study material?</span><i class="pm"><svg><use href="#i-plus"/></svg></i></button><div class="ans"><div><p>Every student receives structured notes, practice sets and revision material for each chapter.</p></div></div></div>
      <div class="qa"><button aria-expanded="false"><span>How can I enquire about admission?</span><i class="pm"><svg><use href="#i-plus"/></svg></i></button><div class="ans"><div><p>Fill in the enquiry form, call us, or message on WhatsApp. We'll arrange a counselling session.</p></div></div></div>
      <div class="qa"><button aria-expanded="false"><span>Where is Decoder Classes located?</span><i class="pm"><svg><use href="#i-plus"/></svg></i></button><div class="ans"><div><p>Our address and a map are in the Contact section below. Directions are one tap away.</p></div></div></div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section class="sec contact" id="contact">
  <div class="wrap contact-grid">
    <div>
      <h2>Let's Start Your Learning Journey</h2>
      <div class="info">
        <div><span class="ico"><svg><use href="#i-pin"/></svg></span><p><strong>Address</strong><span>2nd Floor, Academic Plaza, Main Road,<br>Your City, State – 000000</span></p></div>
        <div><span class="ico"><svg><use href="#i-phone"/></svg></span><p><strong>Phone</strong><span>+91 00000 00000</span></p></div>
        <div><span class="ico"><svg><use href="#i-mail"/></svg></span><p><strong>Email</strong><span>hello@decoderclasses.in</span></p></div>
        <div><span class="ico"><svg><use href="#i-clock"/></svg></span><p><strong>Opening Hours</strong><span>Mon – Sat, 8:00 AM – 8:00 PM</span></p></div>
      </div>
    </div>
    <div class="map" role="img" aria-label="Map placeholder showing Decoder Classes location">
      <svg class="m" viewBox="0 0 600 460" preserveAspectRatio="xMidYMid slice">
        <rect width="600" height="460" fill="#E6ECF8"/>
        <g fill="#D5DEF2"><rect x="30" y="30" width="150" height="110" rx="10"/><rect x="220" y="30" width="120" height="70" rx="10"/><rect x="400" y="40" width="170" height="120" rx="10"/><rect x="30" y="200" width="110" height="100" rx="10"/><rect x="200" y="260" width="140" height="150" rx="10"/><rect x="410" y="230" width="160" height="90" rx="10"/><rect x="40" y="350" width="120" height="80" rx="10"/><rect x="410" y="360" width="150" height="70" rx="10"/></g>
        <rect x="236" y="130" width="90" height="100" rx="12" fill="#C4E6D3"/>
        <g stroke="#fff" stroke-width="16" stroke-linecap="round" fill="none"><path d="M0 170H600"/><path d="M190 0V460"/><path d="M380 0V460"/><path d="M0 330C150 330 250 250 600 290"/></g>
        <g stroke="#fff" stroke-width="7" stroke-linecap="round" fill="none"><path d="M0 100H190M380 190H600M190 400H600"/></g>
      </svg>
      <span class="ph">Map placeholder</span>
      <div class="pin"><span class="lab">Decoder Classes</span><svg><use href="#i-pin-solid"/></svg></div>
      <a href="#contact" class="btn btn-primary btn-sm">Get Directions <span class="arr">→</span></a>
    </div>
  </div>
</section>
</main>

<!-- FOOTER -->
<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <a href="#home" class="logo"><svg width="44" height="44"><use href="#i-logo"/></svg><span><b>DECODER</b><small>CLASSES</small></span></a>
        <p class="tag2">Decode Your Potential. Build Your Future.</p>
        <div class="soc">
          <a href="#" aria-label="Instagram"><svg><use href="#i-ig"/></svg></a>
          <a href="#" aria-label="Facebook"><svg><use href="#i-fb"/></svg></a>
          <a href="#" aria-label="YouTube"><svg><use href="#i-yt"/></svg></a>
          <a href="#" aria-label="WhatsApp"><svg><use href="#i-wa"/></svg></a>
        </div>
      </div>
      <div><h4>Quick Links</h4><ul><li><a href="#home">Home</a></li><li><a href="#about">About</a></li><li><a href="#courses">Courses</a></li><li><a href="#results">Results</a></li><li><a href="#faculty">Faculty</a></li><li><a href="#gallery">Gallery</a></li><li><a href="#contact">Contact</a></li></ul></div>
      <div><h4>Courses</h4><ul><li><a href="#courses">Class 8–9</a></li><li><a href="#courses">Class 10</a></li><li><a href="#courses">Class 11</a></li><li><a href="#courses">Class 12</a></li></ul></div>
      <div><h4>Contact</h4><ul><li>+91 94XXX XXX00</li><li>hello@decoderclasses.in</li><li>2nd Floor, Academic Plaza, Main Road, Your City</li></ul></div>
    </div>
    <div class="copy">© 2026 Decoder Classes. All Rights Reserved.</div>
  </div>
</footer>

<a href="https://wa.me/919400000000" class="wa-fab" aria-label="Chat on WhatsApp"><svg><use href="#i-wa"/></svg></a>
<nav class="mbar" aria-label="Quick contact">
  <a class="call" href="tel:+910000000000"><svg><use href="#i-phone"/></svg>Call Now</a>
  <a class="wa" href="https://wa.me/910000000000"><svg><use href="#i-wa"/></svg>WhatsApp</a>
</nav>

<script>
(function(){
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Hero headline: build two lines, decode "Potential" once on load */
  var h1 = document.querySelector('.hero h1');
  h1.innerHTML = 'Decode Your <span class="hl" id="decode" aria-hidden="true">Potential</span>.<span class="fut">Build Your Future.</span>';
  h1.setAttribute('aria-label','Decode Your Potential. Build Your Future.');
  var el = document.getElementById('decode'), final = 'Potential';
  if(!reduce){
    var chars='01{}<>/=+∑π√∫%#', start=null, dur=1100;
    function frame(t){
      if(!start) start=t;
      var p=Math.min((t-start)/dur,1), done=Math.floor(p*final.length), out='';
      for(var i=0;i<final.length;i++){ out += i<done ? final[i] : chars[Math.floor(Math.random()*chars.length)]; }
      el.textContent = out;
      if(p<1) requestAnimationFrame(frame); else el.textContent = final;
    }
    requestAnimationFrame(frame);
  }

  /* Nav scroll + mobile menu */
  var nav=document.getElementById('nav'), burger=document.getElementById('burger'), menu=document.getElementById('menu');
  function onScroll(){ nav.classList.toggle('scrolled', window.scrollY>30); }
  onScroll(); window.addEventListener('scroll', onScroll, {passive:true});
  function setMenu(open){ menu.classList.toggle('show',open); nav.classList.toggle('open',open); burger.setAttribute('aria-expanded',open); burger.setAttribute('aria-label',open?'Close menu':'Open menu'); }
  burger.addEventListener('click',function(){ setMenu(!menu.classList.contains('show')); });
  menu.addEventListener('click',function(e){ if(e.target.tagName==='A') setMenu(false); });
  document.addEventListener('keydown',function(e){ if(e.key==='Escape') setMenu(false); });

  /* Count-up on viewport entry */
  var counters=document.querySelectorAll('[data-count]');
  function run(n){
    var end=parseFloat(n.dataset.count), dec=parseInt(n.dataset.dec||0,10), suf=n.dataset.suffix||'';
    if(reduce){ n.textContent=end.toFixed(dec)+suf; return; }
    var s=null, d=1400;
    function step(t){ if(!s)s=t; var p=Math.min((t-s)/d,1), e=1-Math.pow(1-p,3); n.textContent=(end*e).toFixed(dec)+suf; if(p<1)requestAnimationFrame(step); }
    requestAnimationFrame(step);
  }
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(es){ es.forEach(function(e){ if(e.isIntersecting){ run(e.target); io.unobserve(e.target);} }); },{threshold:.6});
    counters.forEach(function(c){ io.observe(c); });
  }

  /* Testimonial slider */
  var track=document.getElementById('track');
  function slide(dir){ var c=track.querySelector('.tcard'); track.scrollBy({left:dir*(c.offsetWidth+22),behavior:reduce?'auto':'smooth'}); }
  document.getElementById('prev').addEventListener('click',function(){ slide(-1); });
  document.getElementById('next').addEventListener('click',function(){ slide(1); });

  /* Gallery filter */
  var filters=document.getElementById('filters');
  filters.addEventListener('click',function(e){
    var b=e.target.closest('button'); if(!b) return;
    filters.querySelectorAll('button').forEach(function(x){ x.classList.toggle('on',x===b); });
    var f=b.dataset.f;
    document.querySelectorAll('#gal .g').forEach(function(g){ g.classList.toggle('hide', f!=='all' && g.dataset.c!==f); });
  });

  /* FAQ accordion */
  document.getElementById('acc').addEventListener('click',function(e){
    var b=e.target.closest('button'); if(!b) return;
    var qa=b.parentElement, open=!qa.classList.contains('open');
    qa.classList.toggle('open',open); b.setAttribute('aria-expanded',open);
  });

  /* Enquiry form: client-side validation + PHP/MySQL submission */
  var form=document.getElementById('form');
  form.addEventListener('submit',async function(e){
    var ok=true;
    ['sn','pn','mn','cl'].forEach(function(id){
      var i=document.getElementById(id), v=i.value.trim(), good=!!v;
      if(id==='mn') good=/^[6-9]\d{9}$/.test(v);
      i.parentElement.classList.toggle('bad',!good);
      if(!good) ok=false;
    });
    if(!ok){
      e.preventDefault();
      var bad=form.querySelector('.bad input, .bad select');
      if(bad) bad.focus();
      return;
    }
    e.preventDefault();
    var submitButton=form.querySelector('button[type="submit"]');
    submitButton.disabled=true;
    try{
      var response=await fetch(form.action || window.location.href,{
        method:'POST',
        body:new FormData(form),
        headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
      });
      var result=await response.json();
      alert(result.message || 'We could not submit your enquiry right now. Please try again.');
      if(result.success) form.reset();
    }catch(error){
      alert('We could not submit your enquiry right now. Please try again.');
    }finally{
      submitButton.disabled=false;
    }
  });
  document.getElementById('mn').addEventListener('input',function(){ this.value=this.value.replace(/\D/g,''); });
})();
</script>
</body>
</html>
