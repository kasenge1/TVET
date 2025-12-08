# TVET Revision - Professional Development Plan
**Senior Laravel Developer Approach (20+ Years Experience)**

---

## 🎯 Executive Summary

This plan outlines a systematic, maintainable approach to building the TVET Revision platform using **Laravel 12 + Bootstrap 5** with emphasis on:
- **DRY Principles** (Don't Repeat Yourself) - Reusable components
- **Separation of Concerns** - Clean architecture
- **Performance** - Lightweight, optimized codebase
- **Maintainability** - Easy to extend and modify
- **Security** - Laravel best practices

---

## 📋 Architecture Philosophy

### 1. **Component-Based Templates (Blade Components)**
- Create reusable UI components (cards, buttons, alerts, modals)
- Single source of truth for design elements
- Easy global styling updates

### 2. **Layout Inheritance Strategy**
```
layouts/
├── base.blade.php           # Master template (HTML structure, meta, scripts)
├── guest.blade.php          # Public pages (welcome, auth)
├── admin.blade.php          # Admin dashboard layout
└── student.blade.php        # Student dashboard layout
```

### 3. **Service Layer Pattern**
- Move business logic OUT of controllers
- Services: CourseService, QuestionService, SubscriptionService, etc.
- Controllers become thin orchestrators

### 4. **Repository Pattern (Optional - for complex queries)**
- Abstracts database operations
- Makes testing easier
- Better separation of concerns

### 5. **Middleware Strategy**
- `EnsureUserIsAdmin` - Protect admin routes
- `EnsureUserIsStudent` - Protect student routes
- `CheckSubscriptionStatus` - Verify premium access
- `TrackActivity` - Log user actions

---

## 🗂️ Directory Structure (Final State)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/              # Admin-only controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── CourseController.php
│   │   │   ├── UnitController.php
│   │   │   ├── QuestionController.php
│   │   │   ├── StudentController.php
│   │   │   ├── SubscriptionController.php
│   │   │   └── AnalyticsController.php
│   │   ├── Student/            # Student-only controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── BrowseController.php
│   │   │   ├── SearchController.php
│   │   │   ├── BookmarkController.php
│   │   │   └── SubscriptionController.php
│   │   └── Auth/               # Already exists
│   ├── Middleware/
│   │   ├── EnsureUserIsAdmin.php
│   │   ├── EnsureUserIsStudent.php
│   │   ├── CheckSubscriptionStatus.php
│   │   └── TrackUserActivity.php
│   └── Requests/               # Form validation
│       ├── Admin/
│       │   ├── StoreCourseRequest.php
│       │   ├── StoreUnitRequest.php
│       │   └── StoreQuestionRequest.php
│       └── Student/
│           └── UpdateProfileRequest.php
├── Models/
│   ├── User.php               # Enhanced with relationships
│   ├── Course.php
│   ├── Unit.php
│   ├── Question.php
│   ├── Enrollment.php
│   ├── Bookmark.php
│   ├── Subscription.php
│   └── ActivityLog.php
├── Services/                  # Business logic layer
│   ├── CourseService.php
│   ├── QuestionService.php
│   ├── SubscriptionService.php
│   ├── PaymentService.php
│   ├── AIService.php
│   └── AnalyticsService.php
├── Traits/                    # Reusable model behaviors
│   ├── HasSubscription.php
│   ├── TrackableActivity.php
│   └── HasBookmarks.php
└── View/
    └── Components/            # Blade components
        ├── Alert.php
        ├── Card.php
        ├── StatCard.php
        ├── Modal.php
        └── AdminLayout.php

resources/
├── views/
│   ├── components/            # Reusable UI components
│   │   ├── alert.blade.php
│   │   ├── card.blade.php
│   │   ├── stat-card.blade.php
│   │   ├── modal.blade.php
│   │   ├── pagination.blade.php
│   │   └── sidebar.blade.php
│   ├── layouts/
│   │   ├── base.blade.php     # Master HTML structure
│   │   ├── guest.blade.php    # Public pages
│   │   ├── admin.blade.php    # Admin dashboard
│   │   └── student.blade.php  # Student dashboard
│   ├── partials/              # Partial includes
│   │   ├── admin/
│   │   │   ├── navbar.blade.php
│   │   │   └── sidebar.blade.php
│   │   └── student/
│   │       ├── navbar.blade.php
│   │       └── sidebar.blade.php
│   ├── admin/                 # Admin pages
│   │   ├── dashboard.blade.php
│   │   ├── courses/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   └── show.blade.php
│   │   ├── units/
│   │   ├── questions/
│   │   └── students/
│   ├── student/               # Student pages
│   │   ├── dashboard.blade.php
│   │   ├── browse/
│   │   │   ├── units.blade.php
│   │   │   └── questions.blade.php
│   │   ├── bookmarks.blade.php
│   │   ├── search.blade.php
│   │   └── subscription/
│   └── welcome.blade.php      # Landing page
├── css/
│   └── app.css                # Custom CSS (minimal)
└── js/
    └── app.js                 # Alpine.js components

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php (MODIFY)
│   ├── 2024_12_01_000001_add_fields_to_users_table.php
│   ├── 2024_12_01_000002_create_courses_table.php
│   ├── 2024_12_01_000003_create_units_table.php
│   ├── 2024_12_01_000004_create_questions_table.php
│   ├── 2024_12_01_000005_create_enrollments_table.php
│   ├── 2024_12_01_000006_create_bookmarks_table.php
│   ├── 2024_12_01_000007_create_subscriptions_table.php
│   └── 2024_12_01_000008_create_activity_logs_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── AdminSeeder.php
│   ├── CourseSeeder.php (demo data)
│   └── QuestionSeeder.php (demo data)
└── factories/
    ├── UserFactory.php
    ├── CourseFactory.php
    └── QuestionFactory.php

routes/
├── web.php                    # Main routes file
├── admin.php                  # Admin routes (cleaner separation)
├── student.php                # Student routes
└── auth.php                   # Already exists
```

---

## 🚀 Implementation Phases (Professional Approach)

### **PHASE 1: Foundation & Architecture (Week 1)**
**Goal:** Set up robust, reusable foundation

#### 1.1 Bootstrap Integration & Asset Cleanup
- ✅ Remove Tailwind CSS (replace with Bootstrap 5)
- ✅ Install Bootstrap 5 via npm
- ✅ Create base CSS structure
- ✅ Set up Vite for Bootstrap compilation
- ✅ Add Bootstrap Icons

#### 1.2 Database Schema Design
- ✅ Modify existing users migration (add required fields)
- ✅ Create all 7 new migrations (courses, units, questions, etc.)
- ✅ Add proper indexes for performance
- ✅ Add foreign key constraints
- ✅ Test migrations (migrate/rollback cycle)

#### 1.3 Model Creation & Relationships
- ✅ Extend User model (add fields, relationships)
- ✅ Create all 7 models with:
  - Mass assignment protection
  - Relationships (hasMany, belongsTo, belongsToMany)
  - Accessors/Mutators where needed
  - Query scopes (e.g., `published()`, `premium()`)
- ✅ Add model traits (HasSubscription, TrackableActivity)

#### 1.4 Middleware Setup
- ✅ Create EnsureUserIsAdmin
- ✅ Create EnsureUserIsStudent
- ✅ Create CheckSubscriptionStatus
- ✅ Register in Kernel.php

#### 1.5 Seeder for Initial Data
- ✅ Create AdminSeeder (first admin account)
- ✅ Create sample course data (optional)
- ✅ Test seeding process

**Deliverables:**
- Fully migrated database with all tables
- All models with relationships tested
- Middleware protecting routes
- First admin account created

---

### **PHASE 2: Reusable UI Components (Week 1)**
**Goal:** Build DRY component library

#### 2.1 Base Layout Structure
Create three master layouts:
1. **base.blade.php** - HTML skeleton
   - Meta tags, CSRF token
   - Bootstrap CSS/JS includes
   - Alpine.js
   - Global scripts
   - Yield sections: title, styles, scripts

2. **guest.blade.php** - Public pages
   - Extends base
   - Simple navbar (Logo, Login, Register)
   - Clean footer

3. **admin.blade.php** - Admin dashboard
   - Extends base
   - Top navbar with profile dropdown
   - Left sidebar with navigation
   - Main content area
   - Logout functionality

4. **student.blade.php** - Student dashboard
   - Extends base
   - Top navbar (Course name, subscription badge)
   - Left sidebar (Dashboard, Units, Bookmarks, Search)
   - Main content area
   - Subscription upgrade CTA (if free)

#### 2.2 Blade Components (Reusable)
Create components in `resources/views/components/`:

1. **alert.blade.php** - Success/Error/Warning messages
   ```blade
   <x-alert type="success" dismissible>
       Operation completed successfully!
   </x-alert>
   ```

2. **card.blade.php** - Content container
   ```blade
   <x-card title="Course Details" icon="book">
       {{ $slot }}
   </x-card>
   ```

3. **stat-card.blade.php** - Dashboard statistics
   ```blade
   <x-stat-card title="Total Students" value="245" icon="users" color="primary" />
   ```

4. **modal.blade.php** - Reusable modal dialogs
   ```blade
   <x-modal id="deleteModal" title="Confirm Delete">
       {{ $slot }}
   </x-modal>
   ```

5. **button.blade.php** - Consistent button styling
   ```blade
   <x-button type="primary" size="lg" icon="plus">
       Add Course
   </x-button>
   ```

6. **sidebar-item.blade.php** - Navigation items
   ```blade
   <x-sidebar-item route="admin.dashboard" icon="home">
       Dashboard
   </x-sidebar-item>
   ```

#### 2.3 Partials (Includes)
- `partials/admin/navbar.blade.php`
- `partials/admin/sidebar.blade.php`
- `partials/student/navbar.blade.php`
- `partials/student/sidebar.blade.php`
- `partials/flash-messages.blade.php`

**Deliverables:**
- 3 master layouts
- 6+ reusable components
- 5+ partial views
- Consistent design system

---

### **PHASE 3: Admin Panel - Core CRUD (Week 2)**
**Goal:** Build admin functionality using components

#### 3.1 Admin Routes & Middleware
File: `routes/admin.php`
```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('courses', CourseController::class);
    Route::resource('units', UnitController::class);
    Route::resource('questions', QuestionController::class);
    // ...
});
```

#### 3.2 Admin Dashboard
- Statistics cards (students, courses, questions, revenue)
- Recent enrollments table
- Quick actions (Add Course, Add Question)
- Charts (using Chart.js - install later)

#### 3.3 Course Management (CRUD)
- Index page (table with search, pagination)
- Create form (title, code, description, thumbnail)
- Edit form (reuse create form)
- Delete confirmation modal
- Publish/Unpublish toggle

#### 3.4 Unit Management (CRUD)
- Nested under course (course_id required)
- Drag-to-reorder functionality (Alpine.js)
- Unit count per course displayed

#### 3.5 Question Management (Basic - no AI yet)
- Add main question
- Add sub-question (parent selector)
- Manual answer input (textarea for now)
- Image upload (single image initially)
- Preview before save

**Deliverables:**
- Admin dashboard with stats
- Full CRUD for courses
- Full CRUD for units
- Basic CRUD for questions
- All using reusable components

---

### **PHASE 4: Rich Text Editor & LaTeX Support (Week 2)**
**Goal:** Professional answer formatting

#### 4.1 TinyMCE Integration
- Install TinyMCE via CDN or npm
- Configure toolbar (bold, italic, lists, images, formulas)
- Create blade component for editor
- Replace textareas with TinyMCE

#### 4.2 LaTeX Formula Support
- Install MathJax or KaTeX
- Configure formula rendering
- Add formula toolbar to TinyMCE
- Test rendering in admin preview

#### 4.3 Multiple Image Upload
- Use Intervention Image for processing
- Create upload component
- Image gallery display (drag to reorder)
- Thumbnail generation
- Delete image functionality

**Deliverables:**
- Rich text editor working for questions/answers
- LaTeX formulas rendering correctly
- Multiple image upload per question/answer
- Image optimization pipeline

---

### **PHASE 5: Student Dashboard & Browse (Week 3)**
**Goal:** Student experience - core functionality

#### 5.1 Course Selection (Post-Registration)
- After registration, redirect to course selection
- Display all published courses
- One-time selection (insert into enrollments)
- Redirect to student dashboard

#### 5.2 Student Dashboard
- Stats: Units, Questions, Viewed Today, Bookmarks
- Quick actions: Continue, Browse Units, Search
- Recent activity
- Subscription status badge

#### 5.3 Browse Units
- List all units in enrolled course
- Question count per unit
- Click to view questions

#### 5.4 View Questions
- Display questions with numbering (1, 1a, 1b, 2...)
- Indent sub-questions
- Show/Hide answer toggle (Alpine.js)
- Bookmark button
- LaTeX rendering
- Image lightbox
- Track view count

#### 5.5 AdSense Placeholder
- Create ad component
- Insert every 5 questions
- Hide if user is premium
- Use demo ad block for testing

**Deliverables:**
- Course enrollment flow
- Student dashboard
- Browse units page
- Question viewing with toggle
- Ad placement logic (without actual AdSense)

---

### **PHASE 6: Search & Bookmarks (Week 3)**
**Goal:** Student utility features

#### 6.1 Search Functionality
- Search bar in navbar
- Real-time AJAX search (Alpine.js + Axios)
- Search across question_text AND answer_text
- Highlight matching keywords
- Click to navigate to question

#### 6.2 Bookmark System
- Add/remove bookmark (AJAX)
- My Bookmarks page
- Group by unit
- Sort options (by unit, by date)
- Quick toggle answer

**Deliverables:**
- Working search with highlighting
- Bookmark functionality
- Bookmarks page

---

### **PHASE 7: AI Answer Generation (Week 4)**
**Goal:** AI-assisted content creation

#### 7.1 AI Service Setup
- Create AIService class
- Support multiple providers (OpenAI, Gemini, Claude)
- Configure via .env
- Prompt template for TVET context

#### 7.2 Admin Integration
- "Generate Answer" button
- Loading indicator
- AI response populates editor
- Editable before save
- Mark as AI-generated in DB

#### 7.3 Error Handling
- API timeout handling
- Rate limiting
- Fallback to manual entry

**Deliverables:**
- AI answer generation working
- Support for OpenAI/Gemini/Claude
- Admin can edit AI responses

---

### **PHASE 8: Payment System (Week 4-5)**
**Goal:** Monetization infrastructure

#### 8.1 Subscription Model
- Create SubscriptionService
- Pricing logic (monthly/yearly)
- Subscription status checking
- Auto-expiry handling

#### 8.2 M-Pesa Integration
- Daraja API setup
- STK Push flow
- Payment callback handling
- Transaction logging

#### 8.3 Stripe Integration (Optional - Phase 2)
- Stripe checkout
- Webhook handling
- Receipt generation

#### 8.4 Subscription Management
- Upgrade page (pricing display)
- Payment confirmation
- Email notification
- Admin view subscriptions

**Deliverables:**
- M-Pesa payment working
- Subscription activation/expiry
- Payment receipts via email
- Admin subscription management

---

### **PHASE 9: Google OAuth & Analytics (Week 5)**
**Goal:** Enhanced authentication & insights

#### 9.1 Google OAuth
- Install Socialite
- Configure Google OAuth
- Modify registration flow
- Link existing accounts

#### 9.2 Activity Tracking
- Middleware to log actions
- Track question views
- Track search queries
- Track bookmarks

#### 9.3 Admin Analytics
- Dashboard charts (Chart.js)
- Enrollment trends
- Revenue reports
- Most viewed questions
- Export reports (CSV)

**Deliverables:**
- Google OAuth working
- Activity logging
- Analytics dashboard
- Export functionality

---

### **PHASE 10: Polish & Testing (Week 6)**
**Goal:** Production readiness

#### 10.1 Responsive Design
- Mobile optimization
- Tablet layout
- Desktop enhancements

#### 10.2 Performance
- Query optimization (eager loading)
- Image lazy loading
- Redis caching
- CDN setup (Cloudflare)

#### 10.3 Security Audit
- CSRF protection verified
- XSS prevention
- SQL injection checks
- File upload security
- Rate limiting

#### 10.4 Testing
- Feature tests (CRUD operations)
- Browser tests (Laravel Dusk)
- Payment flow testing
- Email notifications

**Deliverables:**
- Mobile-responsive
- Performance optimized
- Security hardened
- All tests passing

---

## 🎨 Bootstrap 5 Component Standards

### Color Palette
```css
/* Primary: Blue - Education, Trust */
--bs-primary: #2563eb;

/* Success: Green - Growth, Completion */
--bs-success: #10b981;

/* Warning: Orange - Alerts, CTAs */
--bs-warning: #f59e0b;

/* Danger: Red - Errors, Delete */
--bs-danger: #ef4444;

/* Light: Background */
--bs-light: #f8fafc;
```

### Spacing Convention
- Use Bootstrap utilities: `mb-3`, `p-4`, `mt-5`
- Consistent spacing: 8px base unit
- Avoid custom CSS unless necessary

### Typography
- Headings: `h1-h6` classes
- Body: Default Bootstrap (16px base)
- Code/Formulas: `font-monospace`

---

## 📦 Package Installation Schedule

### Week 1 (Foundation)
```bash
# Remove Tailwind, install Bootstrap
npm uninstall tailwindcss @tailwindcss/forms @tailwindcss/vite
npm install bootstrap @popperjs/core bootstrap-icons
composer require intervention/image
```

### Week 2 (Rich Text)
```bash
npm install tinymce
# MathJax via CDN (lighter)
```

### Week 3 (AI)
```bash
composer require openai-php/client  # or google/generative-ai-php
```

### Week 4 (Payments)
```bash
composer require safaricom/mpesa  # M-Pesa SDK
```

### Week 5 (OAuth & Charts)
```bash
composer require laravel/socialite
npm install chart.js
```

---

## 🧪 Testing Strategy

### Unit Tests
- Model relationships
- Service methods
- Helper functions

### Feature Tests
- Authentication flow
- CRUD operations
- Search functionality
- Bookmark actions
- Payment processing

### Browser Tests (Dusk)
- Registration → Enrollment → Browse
- Admin add course → unit → question
- Search → Bookmark → View
- Subscription upgrade flow

---

## 🔐 Security Checklist

- [ ] CSRF tokens on all forms
- [ ] Input validation (Form Requests)
- [ ] XSS prevention (Blade escaping)
- [ ] SQL injection (Eloquent ORM)
- [ ] File upload validation
- [ ] Rate limiting (login, API, AI)
- [ ] Role-based middleware
- [ ] Session security (timeout, regeneration)
- [ ] HTTPS enforcement (production)
- [ ] Environment secrets (.env, never in code)

---

## 📊 Performance Targets

- **Page Load:** < 2 seconds (desktop)
- **Mobile Score:** > 85 (Lighthouse)
- **Database Queries:** < 20 per page
- **API Response:** < 500ms
- **Image Size:** < 200KB (after optimization)

---

## 🚦 Development Workflow

### Daily Routine
1. Pull latest code
2. Run migrations if new
3. Write feature (controller → service → view)
4. Test locally
5. Commit with clear message
6. Push to branch

### Git Strategy
```
main (production)
├── develop (staging)
    ├── feature/admin-courses
    ├── feature/student-browse
    ├── feature/ai-integration
    └── feature/payment-mpesa
```

### Code Review Before Merge
- No hardcoded values
- No SQL injection risks
- Proper validation
- Reusable components used
- Comments for complex logic

---

## 📝 Documentation Standards

### Controller Methods
```php
/**
 * Display a listing of courses.
 *
 * @return \Illuminate\View\View
 */
public function index()
{
    $courses = Course::with('units')->paginate(20);
    return view('admin.courses.index', compact('courses'));
}
```

### Service Methods
```php
/**
 * Create a new course with validation.
 *
 * @param array $data
 * @return Course
 * @throws \Exception
 */
public function createCourse(array $data): Course
{
    // Implementation
}
```

---

## 🎯 Success Metrics (Post-Launch)

### Technical
- [ ] Zero critical bugs in first week
- [ ] < 5 support tickets/day
- [ ] 99% uptime
- [ ] < 3s avg page load

### Business
- [ ] 100 students in first month
- [ ] 10% free → premium conversion
- [ ] 4+ star rating
- [ ] 60% user retention (30 days)

---

## 🛠️ Tools & Resources

### Development
- **IDE:** VSCode / PHPStorm
- **Local Server:** Laragon / XAMPP / Laravel Sail
- **Database Client:** TablePlus / phpMyAdmin
- **API Testing:** Postman / Insomnia
- **Version Control:** Git + GitHub/GitLab

### Design
- **UI Inspiration:** Bootstrap Themes, AdminLTE
- **Icons:** Bootstrap Icons
- **Images:** Unsplash (free stock)

### Monitoring (Production)
- **Errors:** Sentry
- **Uptime:** UptimeRobot
- **Analytics:** Google Analytics
- **Performance:** Lighthouse

---

## 🎓 Best Practices Summary

1. **DRY:** One component, many uses
2. **KISS:** Keep it simple, stupid
3. **SOLID:** Single responsibility principle
4. **Security First:** Validate everything
5. **Performance:** Optimize queries early
6. **Testing:** Write tests as you build
7. **Documentation:** Comment complex logic
8. **Git:** Small, frequent commits
9. **Naming:** Clear, descriptive names
10. **Consistency:** Follow Laravel conventions

---

## 📅 Next Immediate Actions

### Day 1 (Today)
1. ✅ Approve this plan
2. Replace Tailwind with Bootstrap 5
3. Create all database migrations
4. Create all models
5. Create middleware
6. Run migrations + seed admin

### Day 2
1. Build base layouts (guest, admin, student)
2. Create reusable components (alert, card, button, modal)
3. Build admin sidebar/navbar
4. Build student sidebar/navbar

### Day 3
1. Admin dashboard (stats cards)
2. Course CRUD (using components)
3. Test CRUD flow

---

**Ready to start? Let's build this systematically, one component at a time.**

**Next Step:** Approve plan → Begin Day 1 tasks
