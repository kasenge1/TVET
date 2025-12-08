# TVET Revision System - Restructuring Plan

## Overview
A simple, focused revision platform where students:
1. **Register and pick ONE course** (permanent, cannot switch)
2. **Consume questions and answers** on the frontend (not a dashboard)
3. **View rich content** including images, diagrams, and mathematical formulas
4. **See strategic ads** (unless premium)

This is a **read-only learning platform** - no testing, no quizzes, just questions and answers for revision.

---

## Core Principles

1. **One Student = One Course** (permanent choice at registration)
2. **Content Consumption Only** (no tests, no scores, no quizzes)
3. **Frontend Experience** (feels like a website, not a dashboard)
4. **Rich Content Support** (images, diagrams, math equations)
5. **Strategic Ad Placement** (revenue without disrupting learning)
6. **Mobile-First** (most students on phones)

---

## System Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                        REGISTRATION                              │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │  Name: [________________]                                    ││
│  │  Email: [________________]                                   ││
│  │  Password: [________________]                                ││
│  │  Confirm: [________________]                                 ││
│  │                                                              ││
│  │  Select Your Course: [▼ Choose Course____________]          ││
│  │    • Certificate in ICT - Level 4                           ││
│  │    • Diploma in Business Management - Level 5               ││
│  │    • Diploma in Electrical Engineering - Level 6            ││
│  │                                                              ││
│  │  ⚠️ Note: Course selection is permanent                     ││
│  │                                                              ││
│  │  [Create Account]                                            ││
│  └─────────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                     MY COURSE PAGE                               │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │  📚 Diploma in Business Management                          ││
│  │  Level 5 | 8 Units | 245 Questions                          ││
│  └─────────────────────────────────────────────────────────────┘│
│                                                                  │
│  [AD BANNER]                                                     │
│                                                                  │
│  UNITS                                                           │
│  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐             │
│  │ Unit 1       │ │ Unit 2       │ │ Unit 3       │             │
│  │ Principles   │ │ Accounting   │ │ Marketing    │             │
│  │ 32 Questions │ │ 45 Questions │ │ 28 Questions │             │
│  └──────────────┘ └──────────────┘ └──────────────┘             │
│                                                                  │
│  [AD BANNER]                                                     │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                      UNIT PAGE                                   │
│  Breadcrumb: My Course > Unit 1: Principles of Management       │
│                                                                  │
│  [AD BANNER]                                                     │
│                                                                  │
│  QUESTIONS                                                       │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │ Q1. Define the term "management" (3 marks)      [View →]    ││
│  ├─────────────────────────────────────────────────────────────┤│
│  │ Q2. List four functions of management           [View →]    ││
│  ├─────────────────────────────────────────────────────────────┤│
│  │ Q3. Explain the planning process (10 marks)     [View →]    ││
│  │     [Has Diagram 📊]                                        ││
│  └─────────────────────────────────────────────────────────────┘│
│                                                                  │
│  [AD BANNER]                                                     │
│                                                                  │
│  [1] [2] [3] ... [Next]                                          │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    QUESTION PAGE                                 │
│  ← Previous | Question 3 of 32 | Next →                         │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │  QUESTION                                         [★ Save]  ││
│  │  ─────────────────────────────────────────────────────────  ││
│  │  Explain the planning process in management.                ││
│  │  Include a diagram to illustrate your answer.               ││
│  │  (10 marks)                                                 ││
│  │                                                              ││
│  │  [📊 Question Diagram/Image if any]                         ││
│  └─────────────────────────────────────────────────────────────┘│
│                                                                  │
│  [AD BANNER]                                                     │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │  ANSWER                                                     ││
│  │  ─────────────────────────────────────────────────────────  ││
│  │  Planning is the process of setting objectives and          ││
│  │  determining the best course of action to achieve them.     ││
│  │                                                              ││
│  │  The planning process includes:                              ││
│  │  1. Setting objectives                                       ││
│  │  2. Analyzing the environment                                ││
│  │  3. Identifying alternatives                                 ││
│  │  4. Evaluating alternatives                                  ││
│  │  5. Selecting the best alternative                           ││
│  │  6. Implementing the plan                                    ││
│  │  7. Monitoring and controlling                               ││
│  │                                                              ││
│  │  [📊 Answer Diagram showing planning cycle]                 ││
│  │                                                              ││
│  │  Mathematical example:                                       ││
│  │  If budget = $10,000 and cost per unit = $50                ││
│  │  Maximum units = 10,000 ÷ 50 = 200 units                    ││
│  │                                                              ││
│  └─────────────────────────────────────────────────────────────┘│
│                                                                  │
│  [AD BANNER]                                                     │
│                                                                  │
│  [← Previous] [Back to Unit] [Next →]                           │
└─────────────────────────────────────────────────────────────────┘
```

---

## URL Structure (SEO-Optimized)

### Public Routes (No Login Required)
```
/                                                    → Home page
/courses                                             → Browse all courses
/courses/diploma-in-business-management              → Course preview
/courses/diploma-in-business-management/units        → Units list (public preview)
/login                                               → Login page
/register                                            → Registration with course selection
```

### Protected Routes (Login Required - Frontend Layout)
```
/learn                                               → Student's enrolled course overview
/learn/principles-of-management                      → Unit with questions list
/learn/principles-of-management/define-management    → Question with answer
/learn/saved                                         → Bookmarked questions
/account                                             → Profile & subscription
```

### URL Examples (Real-world SEO)
```
PUBLIC (Indexable by Google):
/courses/certificate-in-ict-level-4
/courses/diploma-electrical-engineering-level-5
/courses/diploma-business-management-level-6
/courses/diploma-business-management-level-6/units

PROTECTED (Behind Login):
/learn
/learn/principles-of-management
/learn/principles-of-management/explain-planning-process
/learn/business-accounting/calculate-depreciation
/learn/saved
```

### SEO Benefits
- **Keyword-rich URLs**: Course and unit names in URL
- **Readable structure**: Humans and search engines understand the hierarchy
- **Shareable public pages**: Course previews can be shared and indexed
- **Clean slugs**: No IDs, just descriptive text

---

## Page Designs

### 1. Registration Page
```
┌─────────────────────────────────────────────────────┐
│  TVET Revision - Create Your Account                │
├─────────────────────────────────────────────────────┤
│                                                     │
│  Full Name                                          │
│  [_________________________________________]        │
│                                                     │
│  Email Address                                      │
│  [_________________________________________]        │
│                                                     │
│  Password                                           │
│  [_________________________________________]        │
│                                                     │
│  Confirm Password                                   │
│  [_________________________________________]        │
│                                                     │
│  ┌─────────────────────────────────────────────┐   │
│  │  SELECT YOUR COURSE                         │   │
│  │  ─────────────────────────────────────────  │   │
│  │  ○ Certificate in ICT - Level 4             │   │
│  │    8 Units • 156 Questions                  │   │
│  │                                              │   │
│  │  ● Diploma in Business - Level 5            │   │
│  │    12 Units • 324 Questions                 │   │
│  │                                              │   │
│  │  ○ Diploma in Engineering - Level 6         │   │
│  │    10 Units • 287 Questions                 │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  ⚠️ Important: You cannot change your course       │
│     after registration.                             │
│                                                     │
│  [        Create My Account        ]                │
│                                                     │
│  Already have an account? Login                     │
└─────────────────────────────────────────────────────┘
```

### 2. My Course Page (After Login)
```
┌─────────────────────────────────────────────────────┐
│  [Logo] TVET Revision    [My Course] [Saved] [👤]   │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ┌─────────────────────────────────────────────┐   │
│  │  📚 Diploma in Business Management          │   │
│  │  Level 5                                     │   │
│  │  12 Units • 324 Questions Available          │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  [============ AD BANNER ============]              │
│                                                     │
│  SELECT A UNIT TO START REVISING                   │
│                                                     │
│  ┌─────────────────────────────────────────────┐   │
│  │  1. Principles of Management                │   │
│  │     32 Questions                     [→]    │   │
│  ├─────────────────────────────────────────────┤   │
│  │  2. Business Accounting                     │   │
│  │     45 Questions                     [→]    │   │
│  ├─────────────────────────────────────────────┤   │
│  │  3. Marketing Fundamentals                  │   │
│  │     28 Questions                     [→]    │   │
│  ├─────────────────────────────────────────────┤   │
│  │  4. Business Law                            │   │
│  │     38 Questions                     [→]    │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  [============ AD BANNER ============]              │
│                                                     │
└─────────────────────────────────────────────────────┘
```

### 3. Unit Page (Questions List)
```
┌─────────────────────────────────────────────────────┐
│  [Logo] TVET Revision    [My Course] [Saved] [👤]   │
├─────────────────────────────────────────────────────┤
│  ← Back to Course                                   │
│                                                     │
│  UNIT 1: Principles of Management                  │
│  32 Questions                                       │
│                                                     │
│  [============ AD BANNER ============]              │
│                                                     │
│  ┌─────────────────────────────────────────────┐   │
│  │  Q1                                          │   │
│  │  Define the term "management" and explain    │   │
│  │  its importance in organizations. (5 marks)  │   │
│  │                                    [View →]  │   │
│  ├─────────────────────────────────────────────┤   │
│  │  Q2                                     ★    │   │
│  │  List and explain four functions of          │   │
│  │  management. (8 marks)                       │   │
│  │                                    [View →]  │   │
│  ├─────────────────────────────────────────────┤   │
│  │  Q3                                     📊   │   │
│  │  Using a diagram, explain the planning       │   │
│  │  process in management. (10 marks)           │   │
│  │                                    [View →]  │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  [============ AD BANNER ============]              │
│                                                     │
│  [1] [2] [3] [4] [Next →]                          │
└─────────────────────────────────────────────────────┘

Legend:
★ = Bookmarked/Saved question
📊 = Has diagram/image
```

### 4. Question Page (Question + Answer)
```
┌─────────────────────────────────────────────────────┐
│  [Logo] TVET Revision    [My Course] [Saved] [👤]   │
├─────────────────────────────────────────────────────┤
│  ← Back to Unit 1                                   │
│                                                     │
│  ┌───────────────────────────────────────────────┐ │
│  │ [← Prev]  Question 3 of 32  [Next →]          │ │
│  └───────────────────────────────────────────────┘ │
│                                                     │
│  ┌─────────────────────────────────────────────┐   │
│  │  QUESTION                         [★ Save]  │   │
│  │  ───────────────────────────────────────────│   │
│  │  Using a diagram, explain the planning       │   │
│  │  process in management.                      │   │
│  │                                              │   │
│  │  (10 marks)                                  │   │
│  │                                              │   │
│  │  ┌─────────────────────────────────────┐    │   │
│  │  │                                     │    │   │
│  │  │   [QUESTION IMAGE/DIAGRAM]          │    │   │
│  │  │                                     │    │   │
│  │  └─────────────────────────────────────┘    │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  [============ AD BANNER ============]              │
│                                                     │
│  ┌─────────────────────────────────────────────┐   │
│  │  ANSWER                                     │   │
│  │  ───────────────────────────────────────────│   │
│  │                                              │   │
│  │  Planning is the first and most important   │   │
│  │  function of management. It involves:        │   │
│  │                                              │   │
│  │  1. Setting Objectives                       │   │
│  │     Define what the organization wants       │   │
│  │     to achieve.                              │   │
│  │                                              │   │
│  │  2. Environmental Analysis                   │   │
│  │     Assess internal and external factors.    │   │
│  │                                              │   │
│  │  3. Developing Alternatives                  │   │
│  │     Create multiple courses of action.       │   │
│  │                                              │   │
│  │  ┌─────────────────────────────────────┐    │   │
│  │  │                                     │    │   │
│  │  │   [ANSWER DIAGRAM - Planning Cycle] │    │   │
│  │  │                                     │    │   │
│  │  └─────────────────────────────────────┘    │   │
│  │                                              │   │
│  │  Mathematical Formula Example:               │   │
│  │  ROI = (Gain - Cost) / Cost × 100%          │   │
│  │                                              │   │
│  │  ───────────────────────────────────────────│   │
│  │  [✨ AI Generated Answer]                   │   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  [============ AD BANNER ============]              │
│                                                     │
│  ┌───────────────────────────────────────────────┐ │
│  │ [← Previous Question]  [↑ Unit]  [Next →]     │ │
│  └───────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────┘
```

### 5. Saved Questions Page
```
┌─────────────────────────────────────────────────────┐
│  [Logo] TVET Revision    [My Course] [Saved] [👤]   │
├─────────────────────────────────────────────────────┤
│                                                     │
│  MY SAVED QUESTIONS                                 │
│  12 questions saved                                 │
│                                                     │
│  [============ AD BANNER ============]              │
│                                                     │
│  ┌─────────────────────────────────────────────┐   │
│  │  Unit 1: Principles of Management           │   │
│  │  Q3: Explain the planning process...        │   │
│  │  Saved 2 days ago            [View] [Remove]│   │
│  ├─────────────────────────────────────────────┤   │
│  │  Unit 2: Business Accounting                │   │
│  │  Q15: Calculate depreciation using...       │   │
│  │  Saved 1 week ago            [View] [Remove]│   │
│  ├─────────────────────────────────────────────┤   │
│  │  Unit 4: Business Law                       │   │
│  │  Q7: Distinguish between civil and...       │   │
│  │  Saved 2 weeks ago           [View] [Remove]│   │
│  └─────────────────────────────────────────────┘   │
│                                                     │
│  [============ AD BANNER ============]              │
│                                                     │
└─────────────────────────────────────────────────────┘
```

---

## Mobile Design

```
┌─────────────────────────┐
│ ☰  TVET Revision    👤  │
├─────────────────────────┤
│                         │
│  📚 Business Mgmt       │
│  Level 5 • 324 Qs       │
│                         │
│  [====== AD ======]     │
│                         │
│  ┌───────────────────┐  │
│  │ 1. Principles     │  │
│  │    32 Questions → │  │
│  ├───────────────────┤  │
│  │ 2. Accounting     │  │
│  │    45 Questions → │  │
│  ├───────────────────┤  │
│  │ 3. Marketing      │  │
│  │    28 Questions → │  │
│  └───────────────────┘  │
│                         │
│  [====== AD ======]     │
│                         │
├─────────────────────────┤
│ 🏠   📚   ★   👤        │
│Home Unit Saved Profile  │
└─────────────────────────┘
```

---

## Ad Placement Strategy

### Free Users
| Page | Ads | Positions |
|------|-----|-----------|
| My Course | 2 | After header, Before footer |
| Unit Page | 2 | After 3rd question, Before pagination |
| Question Page | 2 | Between question & answer, After answer |
| Saved Page | 2 | After header, Before footer |

### Premium Users
- **No ads anywhere**
- Clean, uninterrupted revision experience

---

## Technical Implementation

### New Routes (routes/web.php)
```php
// Public - SEO Friendly URLs
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{course:slug}/units', [CourseController::class, 'units'])->name('courses.units');

// Protected - Learning Routes (Frontend Layout)
Route::middleware(['auth'])->prefix('learn')->name('learn.')->group(function () {
    Route::get('/', [LearnController::class, 'index'])->name('index');
    Route::get('/saved', [LearnController::class, 'saved'])->name('saved');
    Route::get('/{unit:slug}', [LearnController::class, 'unit'])->name('unit');
    Route::get('/{unit:slug}/{question:slug}', [LearnController::class, 'question'])->name('question');
    Route::post('/{question}/bookmark', [LearnController::class, 'toggleBookmark'])->name('bookmark');
});

// Account
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::put('/', [AccountController::class, 'update'])->name('update');
    Route::get('/subscription', [AccountController::class, 'subscription'])->name('subscription');
});
```

### Database Changes Required
```php
// Add 'slug' column to courses table
Schema::table('courses', function (Blueprint $table) {
    $table->string('slug')->unique()->after('title');
});

// Add 'slug' column to units table
Schema::table('units', function (Blueprint $table) {
    $table->string('slug')->after('title');
});

// Add 'slug' column to questions table
Schema::table('questions', function (Blueprint $table) {
    $table->string('slug')->after('question_number');
});
```

### Slug Generation (in Models)
```php
// Course.php
protected static function boot()
{
    parent::boot();
    static::creating(function ($course) {
        $course->slug = Str::slug($course->title . '-' . $course->level_display);
    });
}

// Unit.php
protected static function boot()
{
    parent::boot();
    static::creating(function ($unit) {
        $unit->slug = Str::slug($unit->title);
    });
}

// Question.php
protected static function boot()
{
    parent::boot();
    static::creating(function ($question) {
        $baseSlug = Str::slug(Str::limit(strip_tags($question->question_text), 50));
        $question->slug = $question->question_number
            ? $question->question_number . '-' . $baseSlug
            : $baseSlug;
    });
}
```

### New Controller: LearnController
```php
class LearnController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrollment = $user->enrollment;

        if (!$enrollment) {
            return redirect()->route('home')->with('error', 'Please register to access your course.');
        }

        $course = $enrollment->course->load(['units' => function($q) {
            $q->withCount('questions')->orderBy('unit_number');
        }]);

        $totalQuestions = $course->units->sum('questions_count');

        return view('learn.index', compact('course', 'totalQuestions'));
    }

    public function unit(Unit $unit)
    {
        // Verify unit belongs to student's enrolled course
        $enrollment = Auth::user()->enrollment;
        if (!$enrollment || $unit->course_id !== $enrollment->course_id) {
            abort(403, 'You do not have access to this unit.');
        }

        $questions = $unit->questions()
            ->orderBy('order')
            ->paginate(10);

        $savedIds = Auth::user()->bookmarks()->pluck('question_id')->toArray();

        return view('learn.unit', compact('unit', 'questions', 'savedIds'));
    }

    public function question(Unit $unit, Question $question)
    {
        // Verify question belongs to student's enrolled course
        $enrollment = Auth::user()->enrollment;
        if (!$enrollment || $unit->course_id !== $enrollment->course_id) {
            abort(403, 'You do not have access to this question.');
        }

        // Get prev/next for navigation
        $allQuestions = $unit->questions()->orderBy('order')->get(['id', 'slug']);
        $currentIndex = $allQuestions->search(fn($q) => $q->id === $question->id);

        $prev = $currentIndex > 0 ? $allQuestions[$currentIndex - 1] : null;
        $next = $currentIndex < $allQuestions->count() - 1 ? $allQuestions[$currentIndex + 1] : null;

        $isSaved = Auth::user()->bookmarks()->where('question_id', $question->id)->exists();

        return view('learn.question', compact('unit', 'question', 'prev', 'next', 'isSaved', 'currentIndex', 'allQuestions'));
    }

    public function saved()
    {
        $bookmarks = Auth::user()->bookmarks()
            ->with(['question.unit'])
            ->latest()
            ->paginate(10);

        return view('learn.saved', compact('bookmarks'));
    }

    public function toggleBookmark(Question $question)
    {
        $user = Auth::user();
        $bookmark = $user->bookmarks()->where('question_id', $question->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['saved' => false]);
        }

        $user->bookmarks()->create(['question_id' => $question->id]);
        return response()->json(['saved' => true]);
    }
}
```

### Modified Registration
```php
// RegisteredUserController.php
public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'course_id' => ['required', 'exists:courses,id'],
    ]);

    DB::transaction(function () use ($request, &$user) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $request->course_id,
            'enrolled_at' => now(),
        ]);
    });

    event(new Registered($user));
    Auth::login($user);

    return redirect()->route('revision.course');
}
```

---

## Files to Create

```
app/Http/Controllers/
├── LearnController.php         # Main learning/revision controller
├── AccountController.php       # Account settings

database/migrations/
├── add_slug_to_courses.php     # Add slug column
├── add_slug_to_units.php       # Add slug column
├── add_slug_to_questions.php   # Add slug column

resources/views/learn/
├── index.blade.php             # My course overview (/learn)
├── unit.blade.php              # Unit with questions list
├── question.blade.php          # Question + answer page
├── saved.blade.php             # Saved questions

resources/views/components/
├── question-nav.blade.php      # Prev/Next navigation
├── breadcrumb.blade.php        # Breadcrumb navigation
```

## Files to Modify

```
routes/web.php                  # Add /learn routes
resources/views/auth/register.blade.php  # Add course selection
app/Http/Controllers/Auth/RegisteredUserController.php  # Handle enrollment
resources/views/layouts/frontend.blade.php  # Update nav for logged-in users
app/Models/Course.php           # Add slug generation
app/Models/Unit.php             # Add slug generation
app/Models/Question.php         # Add slug generation
```

## Files to Keep (Redirect Old URLs)

```
/student/dashboard      → /learn
/student/questions      → /learn
/student/questions/{id} → /learn/{unit-slug}/{question-slug}
/student/bookmarks      → /learn/saved
```

---

## Implementation Order

### Phase 1: Registration with Course Selection
1. Modify registration form to show course options
2. Update RegisteredUserController to create enrollment
3. Redirect to /my-course after registration

### Phase 2: Revision Pages
1. Create RevisionController
2. Create course overview page
3. Create unit page with questions list
4. Create question detail page with answer

### Phase 3: Navigation & Polish
1. Add prev/next question navigation
2. Add breadcrumbs
3. Add bookmark/save functionality
4. Update frontend navbar for logged-in users

### Phase 4: Ad Integration
1. Place ads strategically on all revision pages
2. Ensure premium users don't see ads
3. Test ad loading and responsiveness

### Phase 5: Mobile Optimization
1. Add bottom navigation for mobile
2. Optimize touch targets
3. Test on various devices

### Phase 6: Cleanup
1. Add redirects from old student routes
2. Remove or deprecate old student dashboard
3. Update all internal links

---

## Confirmation Required

Please confirm before I start implementation:

- [x] One course per student (permanent, no switching)
- [x] Questions and answers only (no testing/quizzes)
- [x] Frontend experience (not dashboard)
- [x] Rich content support (images, diagrams, math)
- [x] Strategic ad placement for free users
- [ ] SEO-friendly URL structure acceptable?
    - `/courses/diploma-business-management-level-6` (public)
    - `/learn/principles-of-management` (unit)
    - `/learn/principles-of-management/explain-planning-process` (question)
- [ ] Ready to start implementation?

---

*Plan revised: December 2024*
*Awaiting approval to begin implementation*
