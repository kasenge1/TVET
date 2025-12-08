# TVET Revision Project - Complete Development Plan

## 1. Project Overview

### Purpose
A specialized Q&A platform for TVET (Technical and Vocational Education and Training) students to access comprehensive revision materials organized by courses and units.

### Core Features
- **Admin**: Manages courses, units, and Q&A content (manual entry, paste, or AI-assisted)
- **Students**: Register for ONE course, browse questions, toggle answers, bookmark favorites
- **Monetization**: Google AdSense (free tier) OR subscription to remove ads

### Key Principles
- Content-first approach
- Aligned with TVET curriculum structure
- Simple, clean interface
- Support for mathematical formulas and technical diagrams
- Flexible AI integration (any provider)

---

## 2. Educational Structure

### Hierarchy
```
Course (e.g., Electrical Installation Level 4)
└── Units (e.g., Unit 1: Basic Electrical Principles)
    └── Questions (e.g., Question 1, 1a, 1b, 1c, 2, 2a...)
        └── Answers (with optional images)
```

### Real TVET Example
```
Course: Electrical Installation Level 4
├── Unit 1: Basic Electrical Principles (42 questions)
│   ├── Question 1: Define electrical resistance
│   ├── Question 1a: State the formula for resistance
│   ├── Question 1b: Calculate resistance given V=12V, I=3A
│   ├── Question 1c: Explain factors affecting resistance
│   └── Question 2: What is Ohm's Law?
├── Unit 2: Electrical Safety (38 questions)
├── Unit 3: Wiring Systems (56 questions)
└── Unit 4: Circuit Protection (29 questions)
```

---

## 3. Database Schema

### Core Tables

#### users
- `id` - Primary key
- `name` - Full name
- `email` - Unique email
- `password` - Hashed password
- `google_id` - Nullable, for OAuth
- `role` - Enum: 'admin', 'student'
- `subscription_tier` - Enum: 'free', 'premium'
- `subscription_expires_at` - Nullable datetime
- `profile_photo_url` - Nullable
- `created_at`, `updated_at`

#### courses
- `id` - Primary key
- `title` - e.g., "Electrical Installation Level 4"
- `code` - e.g., "EI-L4" (TVET course code)
- `description` - Text
- `thumbnail_url` - Nullable
- `level` - Enum: 'certificate', 'diploma', 'higher_diploma'
- `is_published` - Boolean
- `created_by` - Foreign key to users (admin)
- `created_at`, `updated_at`

#### enrollments
- `id` - Primary key
- `user_id` - Foreign key to users
- `course_id` - Foreign key to courses
- `enrolled_at` - Datetime
- `created_at`, `updated_at`
- **Unique constraint**: One student can only enroll in ONE course

#### units
- `id` - Primary key
- `course_id` - Foreign key to courses
- `unit_number` - Integer (1, 2, 3...)
- `title` - e.g., "Basic Electrical Principles"
- `description` - Text, nullable
- `order` - Integer for sorting
- `created_at`, `updated_at`

#### questions
- `id` - Primary key
- `unit_id` - Foreign key to units
- `question_number` - String (e.g., "1", "1a", "1b", "2", "2a")
- `parent_question_id` - Nullable foreign key to questions (for sub-questions)
- `question_text` - Text (HTML supported for formatting)
- `question_image_url` - Nullable (single primary image)
- `question_images` - JSON array (additional images)
- `answer_text` - Text (HTML supported)
- `answer_image_url` - Nullable (single primary image)
- `answer_images` - JSON array (additional images)
- `ai_generated` - Boolean (track if answer was AI-generated)
- `answer_source` - Enum: 'manual', 'ai', 'pasted'
- `order` - Integer for sorting
- `view_count` - Integer (default 0)
- `created_at`, `updated_at`

#### bookmarks
- `id` - Primary key
- `user_id` - Foreign key to users
- `question_id` - Foreign key to questions
- `created_at`, `updated_at`
- **Unique constraint**: user_id + question_id

#### subscriptions
- `id` - Primary key
- `user_id` - Foreign key to users
- `plan` - Enum: 'monthly', 'yearly'
- `amount` - Decimal
- `payment_method` - Enum: 'mpesa'
- `transaction_id` - String, unique
- `status` - Enum: 'active', 'expired', 'cancelled'
- `starts_at` - Datetime
- `expires_at` - Datetime
- `created_at`, `updated_at`

#### activity_logs
- `id` - Primary key
- `user_id` - Nullable foreign key to users
- `action` - String (e.g., 'viewed_question', 'enrolled_course')
- `resource_type` - String (e.g., 'question', 'course')
- `resource_id` - Integer
- `metadata` - JSON (additional context)
- `created_at`

### Relationships
- **User → Enrollment → Course** (One-to-One per student)
- **Course → Units** (One-to-Many)
- **Unit → Questions** (One-to-Many)
- **Question → Sub-questions** (Self-referencing One-to-Many)
- **User → Bookmarks → Questions** (Many-to-Many)
- **User → Subscriptions** (One-to-Many)

---

## 4. User Roles & Permissions

### Admin Capabilities

#### Dashboard
- Total students per course
- Active subscriptions (free vs premium)
- Most viewed questions (top 10)
- Recent enrollments (last 7 days)
- Revenue analytics (monthly/yearly charts)
- New students this week/month

#### Course Management
- Create new courses
- Edit course details (title, code, level, description)
- Upload course thumbnails
- Publish/Unpublish courses
- Delete courses (with confirmation)
- View enrolled students per course

#### Unit Management
- Add units to courses
- Edit unit details (number, title, description)
- Reorder units (drag & drop or up/down arrows)
- Delete units (cascade delete questions warning)
- View questions count per unit

#### Question & Answer Management
- Add main questions (1, 2, 3...)
- Add sub-questions (1a, 1b, 1c...)
- Three input methods for answers:
  1. **Type manually** - Rich text editor
  2. **Paste content** - From external sources
  3. **AI Generate** - Button to auto-generate answer
- Rich text editor with formula support (LaTeX)
- Upload multiple images per question/answer
- Preview question before saving
- Edit existing questions/answers
- Delete questions (with confirmation)
- Track answer source (manual/pasted/AI)
- Bulk delete questions

#### Student Management
- View all registered students
- Filter by course, subscription status
- View individual student activity
- Manually grant/revoke premium access
- Export student list (CSV)

#### Subscription Management
- View all subscriptions (active/expired)
- View payment history
- Filter by payment method
- Revenue breakdown by course
- Manual subscription adjustment

#### Analytics & Reports
- Most viewed questions (all courses)
- Least viewed questions (identify gaps)
- Average questions viewed per student
- Peak usage times
- Bookmark statistics
- Export reports (PDF/CSV)

#### Settings
- Configure AI API (provider, key, model)
- Configure payment gateways (M-Pesa, Stripe)
- AdSense settings (ad placement frequency)
- Subscription pricing (monthly/yearly rates)
- Email notification settings
- System maintenance

### Student Capabilities

#### Dashboard
- Enrolled course overview
- Total units available
- Total questions available
- Questions viewed (today, all-time)
- Bookmarks count
- Subscription status (Free/Premium with expiry)
- Quick access to recent units

#### Browse Content
- View all units in enrolled course
- View questions within each unit
- Support for main questions and sub-questions
- Toggle "Show Answer" for each question
- View images in questions/answers
- Mathematical formulas rendered properly

#### Bookmarks
- Bookmark any question (main or sub)
- View all bookmarked questions
- Group bookmarks by unit
- Remove bookmarks
- Quick toggle answer for bookmarks

#### Search
- Search across all questions in enrolled course
- Keyword search in question text AND answer text
- Real-time filtering
- Highlight matching keywords

#### Account Management
- Update profile (name, email, password)
- View subscription details
- Upgrade to premium (payment page)
- View subscription history
- Logout

### User Restrictions

**Students:**
- Can ONLY enroll in ONE course
- Can ONLY access questions from enrolled course
- Cannot see questions from other courses
- Cannot change enrolled course (must contact admin)
- Free users see ads every 5 questions
- Premium users see no ads

**Admins:**
- Full access to all courses, units, questions
- Can manage all students
- Can switch between courses when adding content
- No public registration (manually created)

---

## 5. Question Numbering & Sub-questions

### Numbering System
```
Question 1        ← Main question
Question 1a       ← Sub-question
Question 1b       ← Sub-question
Question 1c       ← Sub-question
Question 2        ← Main question
Question 2a       ← Sub-question
Question 2b       ← Sub-question
Question 3        ← Main question
```

### Implementation Details
- `parent_question_id` links sub-questions to main question
- `question_number` stores display number (1, 1a, 1b, etc.)
- `order` field for custom sorting within unit
- Sub-questions visually indented in UI
- Each question (main or sub) has independent:
  - Show/Hide answer toggle
  - Bookmark button
  - View count tracking

### Admin Workflow for Sub-questions
1. Select "Add Sub-question" option
2. Choose parent question from dropdown
3. System auto-suggests next letter (if parent is Q1, suggest 1a)
4. Admin can override suggested number
5. Enter question and answer as normal
6. Save

---

## 6. Mathematical & Physics Content Support

### Requirements

#### LaTeX Formula Support
- Mathematical expressions rendered properly
- Examples: `\frac{V}{I} = R`, `E = mc^2`, `\sqrt{x^2 + y^2}`
- Use MathJax or KaTeX for rendering
- Formula toolbar in admin editor (∑, ∫, √, ², ³, π, etc.)

#### Images for Technical Content
- Support for:
  - Circuit diagrams
  - Vector diagrams
  - Mechanical drawings
  - Wiring schematics
  - Equipment photos
- Multiple images per question/answer (up to 5 each)
- Image captions/descriptions

#### Step-by-Step Solutions
- Answers show working for calculations
- Example format:
  ```
  Given: V = 12V, I = 3A
  Using Ohm's Law: R = V/I
  R = 12/3
  R = 4Ω
  ```

#### Units of Measurement
- Proper scientific notation
- Include units in answers (Ω, V, A, kg, m/s, N, etc.)

### Admin Tools Needed
- Rich text editor (TinyMCE) with:
  - LaTeX input mode
  - Formula preview
  - Symbol toolbar
  - Image upload
  - Table support
- Multiple image upload (drag & drop)
- Preview mode (see rendered formulas before saving)

---

## 7. Answer Input Methods (Admin)

### Three Methods

#### 1. Type Manually
- Admin uses rich text editor
- Full formatting control
- Can insert formulas, images, tables
- Best for: Original content creation

#### 2. Paste Content
- Admin copies from external source (Word, PDF, website)
- Pastes into editor
- Formatting preserved (if compatible)
- Admin can edit after pasting
- Best for: Existing question banks

#### 3. AI Generate
- Admin clicks "Generate Answer with AI" button
- System sends question + context to AI API
- AI returns comprehensive answer
- Answer appears in editor (editable)
- Marked as AI-generated in database
- Best for: Quick content creation

### AI Generation Details

**Context Provided to AI:**
- Course name and level
- Unit number and title
- Question text
- Question type (theory/calculation/practical)

**AI Prompt Template:**
```
You are a TVET instructor creating answers for students.

Context:
- Course: {course_name}
- Level: {course_level}
- Unit: {unit_title}

Question: {question_text}

Generate a comprehensive answer that:
1. Is accurate for TVET students
2. Uses clear, simple language
3. For calculations: Show step-by-step working with units
4. For formulas: Use LaTeX notation (e.g., \frac{a}{b})
5. For theory: Include practical examples
6. Keep concise (2-4 paragraphs)

Answer:
```

**Admin Workflow:**
1. Admin enters question text
2. Clicks "Generate Answer with AI"
3. Loading indicator shows (API call in progress)
4. Answer populates in editor
5. Admin reviews and edits if needed
6. Admin saves question
7. Database marks answer source as "ai"

**Supported AI Providers:**
- OpenAI (GPT-4, GPT-3.5-turbo)
- Google Gemini
- Anthropic Claude
- Cohere
- Configurable in `.env` file

---

## 8. Authentication & Registration

### Registration Methods

#### For Students
**Option 1: Email & Password**
- Enter name, email, password
- Email verification required
- Laravel Breeze default flow

**Option 2: Google OAuth**
- Click "Sign in with Google"
- OAuth redirect to Google
- Auto-fill name and email
- No password needed
- Laravel Socialite integration

#### For Admins
- No public registration
- Created manually via:
  - Database seeder
  - Artisan command
  - Super admin panel
- Email & password only (no OAuth for security)

### Registration Flow (Students)
```
Step 1: Choose registration method (Email or Google)
Step 2: Enter/Confirm details
Step 3: Verify email (if email/password method)
Step 4: Select ONE course to enroll in
Step 5: Redirect to student dashboard
Step 6: Send welcome email
```

### Login Flow
- Email/password or Google OAuth
- Remember me option
- Password reset (email link)
- Redirect to appropriate dashboard (admin vs student)

### Session Management
- Session timeout: 2 hours of inactivity
- Remember me: 30 days
- Concurrent sessions: Max 2 devices (prevent account sharing)
- Logout from all devices option

---

## 9. Monetization Model

### Two Tiers Only

#### Free Tier
**Access:**
- Full access to ALL questions in enrolled course
- Show/Hide answer toggle
- Bookmark questions
- Search functionality

**Restrictions:**
- Google AdSense ads displayed:
  - After every 5 questions
  - Sidebar (desktop only)
  - Bottom banner (mobile only)
- Cannot print questions
- Cannot download content

**Revenue:**
- AdSense earnings (variable)

#### Premium Tier (Ad-Free)
**Pricing:**
- Monthly: KES 300/month (~$2.30 USD)
- Yearly: KES 2,500/year (~$19 USD, save ~30%)

**Benefits:**
- **Only benefit: Remove all ads**
- That's it - simple and clear

**Revenue:**
- Subscription fees

### Payment Methods
1. **M-Pesa** (Primary - Kenya market)
   - Daraja API integration
   - Instant confirmation
2. **Stripe** (International credit/debit cards)
   - For students outside Kenya
3. **PayPal** (Optional)
   - If international demand exists

### Subscription Logic
- Manual renewal only (no auto-charge)
- When premium expires:
  - User automatically reverts to free tier
  - No content lockout
  - Ads start showing again
- User can re-subscribe anytime
- No refunds (clearly stated in terms)

### AdSense Implementation
**Ad Placement Rules:**
- Show ads ONLY to free users
- Premium users: Zero ads (no ad containers rendered)
- Ad frequency: Every 5 questions (configurable by admin)
- Ad types: Responsive display ads

**Code Logic:**
```
IF user.subscription_tier == 'premium' AND subscription_expires_at > now()
  → Hide all ads
ELSE
  → Show ads (every 5 questions)
```

---

## 10. Student Dashboard Design

### Layout Structure
```
Header:
- Logo
- Course name (enrolled)
- Subscription status badge (Free/Premium)
- Profile dropdown (Settings, Logout)

Sidebar (Desktop):
- Dashboard (overview)
- Browse Units
- Bookmarks
- Search
- Upgrade (if free user)

Main Content Area:
- Statistics cards
- Quick access links
- Unit list with question counts
```

### Statistics Cards
1. **Units Available** - Total units in course
2. **Total Questions** - All questions in course
3. **Viewed Today** - Questions viewed today
4. **Bookmarks** - Total bookmarked questions

### Activity Summary
- Questions viewed today: 45
- Questions viewed all-time: 1,248
- Most viewed unit: Unit 3 - Wiring Systems
- Last accessed: Unit 2, Question 15

### Quick Actions
- Continue from last position (resume)
- View all units
- Search questions
- My bookmarks

### Browse Units Section
```
Unit 1: Basic Electrical Principles (42 questions)
[View Questions →]

Unit 2: Electrical Safety (38 questions)
[View Questions →]

Unit 3: Wiring Systems (56 questions)
[View Questions →]
```

---

## 11. Admin Dashboard Design

### Layout Structure
```
Header:
- Logo
- "Admin Panel"
- Notifications icon
- Profile dropdown (Settings, Logout)

Sidebar:
- Dashboard (overview)
- Courses
- Units
- Questions
- Students
- Subscriptions
- Analytics
- Settings
```

### Statistics Cards
1. **Total Students** - Count (+ change this week)
2. **Total Courses** - Count (+ change this month)
3. **Total Questions** - Count (+ change this week)
4. **Premium Subscribers** - Count
5. **Monthly Revenue** - Amount in KES
6. **Free Users** - Count

### Charts & Graphs
1. **Enrollment Trend** - Line chart (last 30 days)
2. **Revenue Trend** - Bar chart (last 12 months)
3. **Course Popularity** - Horizontal bar chart

### Top Lists
1. **Most Popular Courses** (by student count)
2. **Most Viewed Questions** (top 10)
3. **Recent Enrollments** (last 10)
4. **Recent Subscriptions** (last 10)

### Quick Actions
- Add New Course
- Add New Question
- View All Students
- Export Reports

---

## 12. Question Viewing Interface

### Display Layout

#### Main Question Card
```
┌─────────────────────────────────────────┐
│ Question 1                    🔖 Bookmark│
├─────────────────────────────────────────┤
│                                          │
│ Define electrical resistance and state  │
│ its SI unit.                             │
│                                          │
│ [Optional: Question Image]               │
│                                          │
├─────────────────────────────────────────┤
│ [Show Answer ▼]                          │
└─────────────────────────────────────────┘
```

#### Answer Revealed
```
┌─────────────────────────────────────────┐
│ Question 1                  🔖 Bookmarked│
├─────────────────────────────────────────┤
│ Define electrical resistance and state  │
│ its SI unit.                             │
├─────────────────────────────────────────┤
│ [Hide Answer ▲]                          │
├─────────────────────────────────────────┤
│ ▼ ANSWER                                 │
│                                          │
│ Electrical resistance is the opposition │
│ to the flow of electric current in a    │
│ conductor. It is measured in Ohms (Ω).  │
│                                          │
│ Formula: R = V/I                         │
│                                          │
│ [Optional: Answer Image]                 │
│                                          │
│ 🤖 AI-Assisted Answer                    │
└─────────────────────────────────────────┘
```

#### Sub-question (Indented)
```
  ┌───────────────────────────────────────┐
  │ Question 1a              🔖 Bookmark   │
  ├───────────────────────────────────────┤
  │ Calculate resistance if V=12V, I=3A   │
  ├───────────────────────────────────────┤
  │ [Show Answer ▼]                        │
  └───────────────────────────────────────┘
```

### Features

#### Show/Hide Toggle
- Default state: Answer hidden
- Click "Show Answer" → Smooth slide-down animation
- Button changes to "Hide Answer ▲"
- Each question independently togglable
- Track view (increment view_count in database)

#### Bookmark Button
- Click to bookmark/unbookmark
- Icon changes (empty → filled)
- Instant feedback (no page reload)
- Syncs to database
- Works for both main and sub-questions

#### Image Display
- Lazy loading (load as user scrolls)
- Click to enlarge (modal/lightbox)
- Multiple images shown as gallery
- Alt text for accessibility

#### Formula Rendering
- LaTeX formulas rendered with MathJax/KaTeX
- Inline formulas: $E = mc^2$
- Block formulas: $$\frac{V}{I} = R$$

#### AdSense Placement (Free Users)
```
Question 1
Question 1a
Question 1b
Question 2
Question 2a

--- ADVERTISEMENT ---  ← After every 5 questions

Question 3
Question 3a
...
```

---

## 13. Search Functionality

### Search Scope
- Search within enrolled course only
- Searches both question text AND answer text
- Case-insensitive matching

### Search Features
- Real-time filtering (as user types)
- Minimum 3 characters to trigger search
- Highlight matching keywords in results
- Show question number and unit
- Click to jump directly to question

### Search Results Display
```
Search: "ohm's law"

Results (3 found):

Unit 1, Question 2
What is Ohm's Law?
Matching text: "...Ohm's Law states that..."

Unit 1, Question 2a
Derive the relationship using Ohm's Law
Matching text: "...according to Ohm's Law..."

Unit 3, Question 5
Apply Ohm's Law to calculate...
Matching text: "...using Ohm's Law: V = IR..."
```

---

## 14. Bookmark System

### Functionality
- Students can bookmark any question (main or sub)
- Unlimited bookmarks
- Quick access from "My Bookmarks" page
- Bookmarks persist across sessions

### Bookmarks Page Layout
```
My Bookmarks (23 questions)

Unit 1: Basic Electrical Principles (8 bookmarks)
├── Question 1: Define electrical resistance
│   [Show Answer] [Remove Bookmark]
├── Question 1b: Calculate resistance...
│   [Show Answer] [Remove Bookmark]
└── Question 5: Explain series circuits
    [Show Answer] [Remove Bookmark]

Unit 2: Electrical Safety (5 bookmarks)
├── Question 2: List PPE required...
└── ...

[Show All] [Group by Unit] [Sort by Date Added]
```

### Features
- Group by unit (default)
- Sort options:
  - By unit (default)
  - By date added (newest first)
  - Alphabetically
- Quick toggle answer (no need to navigate to original)
- Remove bookmark button
- Export bookmarks (PDF/text file - premium only?)

---

## 15. Image Handling

### Upload Requirements
- **Supported formats:** JPG, PNG, GIF, SVG
- **Max file size:** 5MB per image
- **Max images per question:** 5
- **Max images per answer:** 5

### Image Processing (Automatic)
1. **Validation**
   - Check file type
   - Check file size
   - Scan for malware (optional)

2. **Optimization**
   - Resize to max 1200px width (maintain aspect ratio)
   - Compress quality to 80%
   - Convert to WebP format (with fallback to original)
   - Generate thumbnail (300px width)

3. **Storage**
   - Store in `/storage/app/public/questions/` or `/answers/`
   - Organized by year/month (e.g., `/questions/2024/11/`)
   - Unique filename (hash + timestamp)
   - Optional: Cloud storage (AWS S3, DigitalOcean Spaces)

### Display Features
- Lazy loading (improve page speed)
- Responsive images (srcset for different screen sizes)
- Click to enlarge (lightbox/modal)
- Zoom in/out capability
- Download button (premium users only?)
- Alt text (required - for accessibility)

### Admin Upload Interface
- Drag & drop zone
- Multi-file upload
- Progress bar for each file
- Preview thumbnails
- Reorder images (drag to reorder)
- Delete image button
- Add caption/description per image

---

## 16. Analytics & Tracking

### Student Activity Tracking

**Events to Track:**
- User registration (timestamp, course enrolled)
- Question viewed (question_id, timestamp)
- Answer revealed (question_id, timestamp)
- Bookmark added/removed (question_id, timestamp)
- Search performed (query, results count, timestamp)
- Session start/end (duration calculation)
- Subscription purchase (plan, amount, timestamp)

**Storage:**
- `activity_logs` table for detailed events
- Aggregated daily/weekly stats for performance

### Admin Analytics

#### Dashboard Metrics
1. **User Metrics**
   - Total students (all time)
   - New students (today, this week, this month)
   - Active students (viewed questions in last 7 days)
   - Inactive students (no activity in 30+ days)

2. **Content Metrics**
   - Total courses
   - Total units
   - Total questions
   - Questions per course (average)
   - Most viewed questions (top 10)
   - Least viewed questions (bottom 10)

3. **Engagement Metrics**
   - Average questions viewed per student
   - Average session duration
   - Bookmark rate (% of students who bookmark)
   - Search usage (% of students who search)

4. **Revenue Metrics**
   - Total revenue (all time)
   - Revenue this month
   - Revenue this year
   - Average revenue per student
   - Conversion rate (free → premium)
   - Churn rate (premium → free)

#### Reports Available
1. **Student Report**
   - List of all students
   - Enrollment date, course, subscription status
   - Activity summary (questions viewed, bookmarks)
   - Export as CSV/PDF

2. **Course Report**
   - Enrollment count per course
   - Questions count per course
   - Average views per question
   - Popular units

3. **Revenue Report**
   - Daily/weekly/monthly revenue breakdown
   - Payment method distribution
   - Subscription plan distribution
   - Revenue by course

4. **Activity Report**
   - Peak usage times (hourly heatmap)
   - Daily active users (DAU)
   - Weekly active users (WAU)
   - Monthly active users (MAU)

---

## 17. Technology Stack

### Backend
- **Framework:** Laravel 11 (latest stable)
- **Language:** PHP 8.2+
- **Database:** MySQL 8.0
- **ORM:** Eloquent
- **Authentication:** Laravel Breeze + Socialite (Google OAuth)
- **API Auth:** Laravel Sanctum
- **Image Processing:** Intervention Image
- **Payments:** M-Pesa Daraja API, Stripe PHP SDK
- **Queue:** Laravel Queue (for async tasks)
- **Cache:** Redis (for session, query caching)

### Frontend
- **CSS Framework:** Bootstrap 5.3
- **JavaScript:** Alpine.js (lightweight interactivity)
- **Rich Text Editor:** TinyMCE
- **Math Rendering:** MathJax or KaTeX
- **Charts:** Chart.js (admin dashboard)
- **Icons:** Bootstrap Icons or Font Awesome
- **Image Lightbox:** Lightbox2 or GLightbox

### AI Integration
- **Flexible Provider Support:**
  - OpenAI API (GPT-4, GPT-3.5-turbo)
  - Google Gemini API
  - Anthropic Claude API
  - Cohere API
- **Configuration:** `.env` file for API keys and model selection

### DevOps & Hosting
- **Version Control:** Git + GitHub/GitLab
- **Server:** VPS (DigitalOcean, Linode, Vultr)
- **Web Server:** Nginx or Apache
- **SSL:** Let's Encrypt (free certificates)
- **CDN:** Cloudflare (free tier)
- **File Storage:** Local or AWS S3/DigitalOcean Spaces
- **Backup:** Laravel Backup package (daily automated)
- **Monitoring:** Laravel Telescope (development), Sentry (production errors)

### Development Tools
- **Package Manager:** Composer (PHP), npm (JavaScript)
- **Asset Bundling:** Vite (Laravel default)
- **Code Quality:** PHPStan, PHP CS Fixer
- **Testing:** PHPUnit, Laravel Dusk (browser testing)

---

## 18. Implementation Phases

### Phase 1: Foundation (Weeks 1-2)
**Goals:**
- Set up Laravel project
- Configure environment (.env)
- Design and implement database schema
- Run migrations
- Set up authentication (Breeze + Socialite)

**Deliverables:**
- Working Laravel installation
- Database with all tables
- User registration/login (email + Google)
- Basic layouts (admin & student)
- Role-based middleware (admin/student separation)

---

### Phase 2: Admin Panel - Course & Unit Management (Week 3)
**Goals:**
- Build admin dashboard with statistics
- Implement course CRUD
- Implement unit CRUD
- Image upload for course thumbnails

**Deliverables:**
- Admin dashboard showing key metrics
- Course management (create, edit, delete, publish)
- Unit management (create, edit, delete, reorder)
- Admin navigation sidebar

---

### Phase 3: Admin Panel - Question & Answer Management (Week 4)
**Goals:**
- Implement question CRUD
- Support main questions and sub-questions
- Rich text editor integration (TinyMCE)
- Image upload for questions/answers
- LaTeX formula input support

**Deliverables:**
- Add/edit question form with rich editor
- Sub-question functionality (parent linking)
- Multiple image upload (up to 5 per question/answer)
- Formula toolbar and preview
- Answer source tracking (manual/pasted/ai)

---

### Phase 4: AI Integration (Week 5)
**Goals:**
- Configure AI API (flexible provider)
- Implement "Generate Answer" functionality
- Prompt engineering for TVET context
- Answer review workflow

**Deliverables:**
- AI provider configuration in .env
- "Generate Answer" button in admin panel
- AI-generated answers editable by admin
- Database flag for AI-generated answers
- Error handling and rate limiting

---

### Phase 5: Student Experience - Browse & View (Week 6)
**Goals:**
- Student enrollment flow (course selection)
- Student dashboard with statistics
- Browse units and questions
- Show/Hide answer toggle
- LaTeX rendering (MathJax/KaTeX)
- Image display with lightbox

**Deliverables:**
- Course selection page (post-registration)
- Student dashboard with stats cards
- Unit listing page
- Question viewing page
- Show/hide answer functionality
- Math formulas rendered properly
- Responsive design (mobile, tablet, desktop)

---

### Phase 6: Student Features - Search & Bookmarks (Week 7)
**Goals:**
- Search functionality (across enrolled course)
- Bookmark system
- My Bookmarks page
- Activity tracking

**Deliverables:**
- Search bar with real-time filtering
- Bookmark/unbookmark functionality
- Bookmarks page (grouped by unit)
- Activity logs (question views, bookmarks)
- Student stats on dashboard (questions viewed, etc.)

---

### Phase 7: Monetization - AdSense & Payments (Week 8)
**Goals:**
- Google AdSense integration
- Ad placement logic (every 5 questions)
- M-Pesa payment integration
- Stripe payment integration
- Subscription management

**Deliverables:**
- AdSense code integrated (free users only)
- Ad containers hidden for premium users
- M-Pesa payment flow (STK Push)
- Stripe checkout integration
- Subscription CRUD (create, extend, expire)
- Subscription status checking (middleware)
- Payment confirmation emails

---

### Phase 8: Admin Analytics & Reports (Week 9)
**Goals:**
- Enhanced admin dashboard with charts
- Analytics pages (students, courses, revenue)
- Report generation (CSV/PDF export)
- Activity tracking visualization

**Deliverables:**
- Charts (enrollment trends, revenue trends)
- Top lists (popular courses, viewed questions)
- Student activity reports
- Revenue reports
- Export functionality (CSV)
- Heatmaps (peak usage times)

---

### Phase 9: Polish & Testing (Week 10)
**Goals:**
- Responsive design refinement
- Dark mode implementation (optional)
- Performance optimization
- Security audit
- User acceptance testing (UAT)

**Deliverables:**
- Mobile-friendly UI (all pages)
- Optimized images (lazy loading, WebP)
- Query optimization (eager loading, indexing)
- Security fixes (XSS, CSRF, SQL injection prevention)
- Bug fixes from UAT
- Documentation (user guides)

---

### Phase 10: Deployment & Launch (Week 11)
**Goals:**
- Production server setup
- Database migration
- SSL configuration
- Domain setup
- Monitoring setup
- Soft launch (beta testers)

**Deliverables:**
- Live production site (HTTPS)
- Domain configured (e.g., tvetrevision.co.ke)
- Database backed up daily
- Error monitoring (Sentry)
- Email server configured (SendGrid/Mailgun)
- Beta test with 20-50 students
- Soft launch announcement

---

## 19. Security Considerations

### Authentication & Authorization
- **Password Requirements:**
  - Minimum 8 characters
  - At least one uppercase letter
  - At least one number
  - At least one special character

- **Session Security:**
  - CSRF protection (Laravel default)
  - Session timeout (2 hours inactivity)
  - Secure cookies (httpOnly, secure flags)
  - Session regeneration on login

- **Role-Based Access:**
  - Middleware for admin routes
  - Middleware for student routes
  - API endpoints protected with Sanctum tokens

### Data Protection
- **Encryption:**
  - All sensitive data encrypted at rest (Laravel Crypt)
  - HTTPS only (redirect HTTP to HTTPS)
  - API keys stored in .env (never in code)

- **Input Validation:**
  - Laravel Request validation on all forms
  - Sanitize user inputs (prevent XSS)
  - File upload validation (type, size, malware scan)

- **SQL Injection Prevention:**
  - Use Eloquent ORM exclusively
  - Never use raw queries with user input
  - Parameterized queries if raw SQL needed

### Rate Limiting
- **Login Attempts:** Max 5 per minute per IP
- **API Calls:** Max 60 per minute per user
- **AI Generation:** Max 100 per day per admin
- **Search:** Max 30 per minute per user

### Content Security
- **Image Uploads:**
  - Validate file types (whitelist)
  - Check file size limits
  - Scan for malicious content
  - Store outside public directory

- **Rich Text Editor:**
  - Strip dangerous HTML tags (<script>, <iframe>)
  - Allow only safe tags (<p>, <strong>, <img>, etc.)
  - Sanitize on save and display

### GDPR Compliance (if targeting EU students)
- Cookie consent banner
- Privacy policy page
- Terms of service page
- Data export feature (user can download their data)
- Account deletion (soft delete with 30-day grace)

---

## 20. Performance Optimization

### Database Optimization
- **Indexing:**
  - Index foreign keys
  - Index frequently queried columns (email, course_id, etc.)
  - Composite indexes where needed

- **Query Optimization:**
  - Use eager loading (with()) to prevent N+1 queries
  - Paginate results (20 items per page)
  - Cache frequent queries (course lists, units)
  - Use database query logging to identify slow queries

### Caching Strategy
- **Redis for:**
  - Session storage
  - Query result caching (course lists, unit counts)
  - User progress data
  - API rate limiting counters

- **Cache Duration:**
  - Course lists: 1 hour
  - Unit lists: 30 minutes
  - Question counts: 15 minutes
  - User stats: 5 minutes

### Asset Optimization
- **Images:**
  - Lazy loading (load as user scrolls)
  - Responsive images (srcset)
  - WebP format with fallback
  - CDN for static assets (Cloudflare)

- **JavaScript & CSS:**
  - Minify and bundle (Vite)
  - Defer non-critical JS
  - Inline critical CSS
  - Browser caching (set appropriate headers)

### Queue System
- **Background Jobs:**
  - Send welcome emails
  - Generate reports (PDF/CSV)
  - Process payment confirmations
  - Update analytics aggregates
  - Clean up expired sessions

- **Implementation:**
  - Laravel Queue with Redis driver
  - Laravel Horizon for monitoring
  - Retry failed jobs (3 attempts)

---

## 21. Email Notifications

### Automated Emails

#### For Students
1. **Welcome Email** (on registration)
   - Subject: "Welcome to TVET Revision!"
   - Content: Welcome message, course enrolled, next steps
   - CTA: "Start Browsing Questions"

2. **Subscription Confirmation** (on payment)
   - Subject: "Premium Subscription Activated"
   - Content: Confirmation, plan details, expiry date
   - Receipt attached (PDF)

3. **Subscription Expiry Reminder**
   - Subject: "Your premium subscription expires soon"
   - Timing: 7 days before expiry
   - Content: Reminder, renewal link
   - CTA: "Renew Subscription"

4. **Subscription Expired**
   - Subject: "Your premium subscription has expired"
   - Timing: On expiry date
   - Content: Notification, features now limited, renewal link
   - CTA: "Renew Now"

5. **Password Reset**
   - Subject: "Reset Your Password"
   - Content: Reset link (expires in 1 hour)

#### For Admins
1. **New Student Registration**
   - Daily digest (if multiple registrations)
   - Student name, email, course enrolled

2. **New Subscription**
   - Real-time notification
   - Student name, plan, amount

3. **Payment Failed**
   - Real-time notification
   - Student name, reason, transaction ID

### Email Service
- **Provider Options:**
  - SendGrid (free tier: 100 emails/day)
  - Mailgun (free tier: 5,000 emails/month)
  - Amazon SES (cheap, reliable)
  - SMTP (Gmail, custom server)

- **Configuration:**
  - Set up in .env file
  - Test in development (Mailtrap)
  - Queue emails (don't send synchronously)

---

## 22. Key Features Summary

### Must-Have (MVP)
✅ User registration (email + Google OAuth)
✅ Course, Unit, Question management (admin)
✅ Three answer input methods (manual, paste, AI)
✅ LaTeX formula support
✅ Image upload (multiple per question/answer)
✅ Show/hide answer toggle (student)
✅ Bookmark questions (student)
✅ Search questions (student)
✅ Google AdSense integration (free users)
✅ Subscription payment (M-Pesa + Stripe)
✅ Remove ads (premium users)
✅ Admin dashboard (statistics)
✅ Student dashboard (statistics)

### Nice-to-Have (Phase 2)
⭕ Dark mode toggle
⭕ Print questions (PDF export)
⭕ Question difficulty levels (easy/medium/hard)
⭕ Student notes (private annotations)
⭕ Bulk import questions (CSV)
⭕ Question versioning (edit history)
⭕ Email announcements (admin → students)
⭕ Progress indicator (% of questions viewed)
⭕ Multi-language support (English + Swahili)
⭕ PWA (offline access)
⭕ Native mobile apps (Android/iOS)

---

## 23. Design Guidelines

### Color Scheme
- **Primary:** #2563eb (Blue - trust, education)
- **Secondary:** #10b981 (Green - success, growth)
- **Accent:** #f59e0b (Orange - highlights, CTAs)
- **Background:** #f8fafc (Light gray)
- **Text:** #1e293b (Dark slate)
- **Borders:** #e2e8f0 (Light gray)
- **Error:** #ef4444 (Red)
- **Success:** #10b981 (Green)

### Typography
- **Headings:** Inter or Poppins (bold, clean)
- **Body:** Inter or Open Sans (readable)
- **Code/Formulas:** Fira Code or JetBrains Mono (monospace)

### UI Components
- **Buttons:**
  - Primary: Blue background, white text
  - Secondary: White background, blue border
  - Danger: Red background, white text
  - Rounded corners (6px)
  - Hover states (darken 10%)

- **Cards:**
  - White background
  - Subtle shadow (0 1px 3px rgba(0,0,0,0.1))
  - Rounded corners (8px)
  - Padding: 20px

- **Forms:**
  - Clear labels (above inputs)
  - Placeholder text (light gray)
  - Validation errors (red text below input)
  - Success states (green border)

### Responsive Breakpoints
- **Mobile:** 320px - 767px
- **Tablet:** 768px - 1023px
- **Desktop:** 1024px+

### Accessibility
- **WCAG 2.1 AA Compliance:**
  - Color contrast ratio: 4.5:1 minimum
  - Keyboard navigation (all interactive elements)
  - Alt text for all images
  - Screen reader support (ARIA labels)
  - Focus indicators (visible outlines)

---

## 24. Testing Strategy

### Manual Testing
- **Cross-browser:**
  - Chrome (latest)
  - Firefox (latest)
  - Safari (latest)
  - Edge (latest)

- **Cross-device:**
  - Mobile (375px, 414px widths)
  - Tablet (768px, 1024px widths)
  - Desktop (1920px width)

- **User Flows:**
  - Student registration → enrollment → browse questions
  - Student search → bookmark → view bookmarks
  - Student upgrade → payment → ad removal
  - Admin login → add course → add unit → add question
  - Admin use AI → review answer → publish

### Automated Testing
- **Unit Tests (PHPUnit):**
  - Model relationships
  - Helper functions
  - Business logic

- **Feature Tests (PHPUnit):**
  - API endpoints (CRUD operations)
  - Authentication (login, register, logout)
  - Payment processing
  - Email sending

- **Browser Tests (Laravel Dusk):**
  - End-to-end user flows
  - JavaScript interactions (toggle, bookmark)
  - Form submissions

### Load Testing
- **Tools:** JMeter or LoadForge
- **Scenarios:**
  - 100 concurrent users browsing questions
  - 50 admins adding questions simultaneously
  - 10 payment transactions per minute

- **Performance Targets:**
  - Page load: < 2 seconds
  - API response: < 500ms
  - Database queries: < 100ms

---

## 25. Deployment Checklist

### Pre-Deployment
- [ ] All tests passing (unit, feature, browser)
- [ ] Database migrations ready
- [ ] Seeders for initial data (admin account, sample course)
- [ ] Environment variables configured (.env.production)
- [ ] API keys secured (AI, payments, AdSense)
- [ ] SSL certificate obtained
- [ ] Domain DNS configured
- [ ] Backup strategy in place

### Server Setup
- [ ] VPS provisioned (DigitalOcean, Linode, etc.)
- [ ] Ubuntu 22.04 LTS installed
- [ ] Nginx or Apache installed
- [ ] PHP 8.2+ installed
- [ ] MySQL 8.0 installed
- [ ] Redis installed
- [ ] Composer installed
- [ ] Node.js & npm installed
- [ ] SSL certificate installed (Let's Encrypt)

### Application Deployment
- [ ] Clone repository to server
- [ ] Install PHP dependencies (composer install --no-dev)
- [ ] Install Node dependencies (npm install)
- [ ] Build assets (npm run build)
- [ ] Set file permissions (storage, bootstrap/cache)
- [ ] Run migrations (php artisan migrate --force)
- [ ] Seed database (php artisan db:seed --force)
- [ ] Link storage (php artisan storage:link)
- [ ] Optimize (php artisan optimize)
- [ ] Start queue worker (systemd service)
- [ ] Configure cron job (Laravel scheduler)

### Post-Deployment
- [ ] Test all user flows (registration, login, browse, payment)
- [ ] Test admin panel (add course, unit, question)
- [ ] Test AI generation
- [ ] Test payment gateways (M-Pesa, Stripe)
- [ ] Verify emails are sending
- [ ] Check AdSense ads displaying (free users)
- [ ] Check ads hidden (premium users)
- [ ] Set up monitoring (Sentry, uptime monitoring)
- [ ] Set up backups (daily automated)
- [ ] Announce soft launch (beta testers)

---

## 26. Maintenance & Support

### Regular Maintenance
- **Daily:**
  - Monitor error logs (Sentry)
  - Check payment confirmations
  - Review new student registrations

- **Weekly:**
  - Review analytics (enrollments, views, revenue)
  - Check server resources (CPU, memory, disk)
  - Backup verification (restore test)

- **Monthly:**
  - Update dependencies (composer, npm)
  - Security patches (Laravel, PHP)
  - Review and respond to user feedback
  - Content audit (add new questions)

### Support Channels
- **Email Support:** support@tvetrevision.co.ke
  - Response time: 24 hours (weekdays)
  - Premium users: Priority support

- **FAQ Page:**
  - How to register
  - How to upgrade to premium
  - Payment issues
  - Question issues (report errors)

- **Admin Contact Form:**
  - For students to report issues
  - Auto-creates support ticket

---

## 27. Success Metrics

### Launch Targets (First 3 Months)
- **Users:** 500 registered students
- **Courses:** 5 courses with 100+ questions each
- **Conversion:** 10% free → premium (50 subscribers)
- **Revenue:** KES 15,000/month (~$115 USD)
- **Engagement:** 70% monthly active users (MAU)
- **Satisfaction:** 4+ stars (out of 5) in feedback

### Growth Targets (First Year)
- **Users:** 5,000 registered students
- **Courses:** 15 courses
- **Questions:** 10,000+ total
- **Premium:** 500 subscribers
- **Revenue:** KES 150,000/month (~$1,150 USD)
- **Retention:** 60%+ users return monthly

### Key Performance Indicators (KPIs)
- **Acquisition:** New student registrations per week
- **Activation:** % of students who view 10+ questions
- **Retention:** % of students active after 30 days
- **Revenue:** Monthly recurring revenue (MRR)
- **Referral:** % of students from referrals (future feature)

---

## 28. Future Enhancements (Roadmap)

### Phase 2 (Months 4-6)
- Dark mode toggle
- Print/download questions (PDF)
- Question difficulty levels
- Student notes (private annotations)
- Bulk import questions (CSV)
- Progress tracking (% viewed per unit)

### Phase 3 (Months 7-9)
- Multi-language support (English + Swahili)
- Email announcements (admin → all students)
- Discussion forum (moderated)
- Question upvoting (most helpful)
- Instructor accounts (create & sell courses)

### Phase 4 (Months 10-12)
- Mobile apps (Android + iOS)
- Offline mode (PWA)
- Video explanations (embed YouTube)
- Live chat support
- Referral program (earn credits)
- Course certificates (upon completion)

---

## 29. Critical Decisions Needed

Before starting development, confirm these decisions:

### 1. Course Enrollment
**Question:** Can students enroll in multiple courses or just ONE?
**Recommendation:** ONE course per student (simpler for MVP)
**Your Decision:** _____________

### 2. AI Provider
**Question:** Which AI API to use initially?
**Options:** OpenAI (GPT-4), Google Gemini, Anthropic Claude
**Recommendation:** OpenAI GPT-3.5-turbo (cheap, reliable)
**Your Decision:** _____________

### 3. Payment Priority
**Question:** Which payment method to implement first?
**Options:** M-Pesa only, Stripe only, or both simultaneously
**Recommendation:** M-Pesa first (Kenya market), Stripe in Phase 2
**Your Decision:** _____________

### 4. Subscription Pricing
**Question:** Confirm pricing (in KES)
**Proposed:** Monthly KES 300, Yearly KES 2,500
**Your Decision:** _____________

### 5. Public Preview
**Question:** Should non-registered users see sample questions?
**Recommendation:** Yes (5 questions without answers as teaser)
**Your Decision:** _____________

### 6. Admin Accounts
**Question:** How many admin accounts needed at launch?
**Recommendation:** 1 super admin (you), can add more later
**Your Decision:** _____________

### 7. Target Launch Date
**Question:** When do you want to launch (soft launch)?
**Recommendation:** 11 weeks from project start
**Your Target Date:** _____________

### 8. Domain Name
**Question:** Do you have a domain name?
**Example:** tvetrevision.co.ke
**Your Domain:** _____________

### 9. Hosting Budget
**Question:** Monthly hosting budget?
**Options:** 
  - $5-12/month (shared/basic VPS)
  - $20-50/month (better VPS + cloud storage)
**Your Budget:** _____________

### 10. Content Ready?
**Question:** Do you have existing question banks to migrate?
**Options:** Yes (how many questions?), No (will create from scratch)
**Your Answer:** _____________

---

## 30. Next Steps

Once decisions above are confirmed:

1. **Week 1 Actions:**
   - Set up development environment
   - Create GitHub repository
   - Initialize Laravel project
   - Configure .env (database, AI API key)
   - Design database schema (finalize)
   - Create migrations

2. **Week 2 Actions:**
   - Implement authentication (Breeze + Socialite)
   - Create admin and student layouts
   - Build role middleware
   - Seed initial admin account
   - Test registration/login flows

3. **Week 3+ Actions:**
   - Follow implementation phases (section 18)
   - Weekly progress reviews
   - Adjust timeline as needed

---

## 31. Contact & Support

### Project Documentation
- **This Document:** Master development plan
- **Repository:** _______________ (add GitHub URL)
- **Trello/Jira:** _______________ (add project management link)

### Development Team
- **Lead Developer:** _______________
- **Backend Developer:** _______________
- **Frontend Developer:** _______________
- **UI/UX Designer:** _______________

### Stakeholders
- **Project Owner:** _______________
- **Content Creator:** _______________
- **Marketing:** _______________

---

## Appendix A: Glossary

- **TVET:** Technical and Vocational Education and Training
- **MVP:** Minimum Viable Product
- **CRUD:** Create, Read, Update, Delete
- **OAuth:** Open Authorization (for Google sign-in)
- **AdSense:** Google's advertising platform
- **M-Pesa:** Mobile money service (Kenya)
- **LaTeX:** Typesetting system for mathematical formulas
- **MathJax/KaTeX:** JavaScript libraries for rendering LaTeX
- **API:** Application Programming Interface
- **VPS:** Virtual Private Server
- **SSL:** Secure Sockets Layer (HTTPS encryption)
- **CDN:** Content Delivery Network

---

## Appendix B: Useful Resources

### Laravel Documentation
- Official Docs: https://laravel.com/docs
- Breeze: https://laravel.com/docs/starter-kits#laravel-breeze
- Socialite: https://laravel.com/docs/socialite
- Eloquent ORM: https://laravel.com/docs/eloquent

### AI APIs
- OpenAI: https://platform.openai.com/docs
- Google Gemini: https://ai.google.dev/docs
- Anthropic Claude: https://docs.anthropic.com

### Payment Gateways
- M-Pesa Daraja: https://developer.safaricom.co.ke
- Stripe: https://stripe.com/docs

### Frontend Libraries
- Bootstrap: https://getbootstrap.com
- Alpine.js: https://alpinejs.dev
- TinyMCE: https://www.tiny.cloud/docs
- MathJax: https://docs.mathjax.org
- Chart.js: https://www.chartjs.org/docs

---

**END OF DOCUMENT**

---

**Document Version:** 1.0  
**Last Updated:** November 30, 2024  
**Status:** Ready for Development  

**Approval:**
- [ ] Project Owner Review
- [ ] Technical Lead Review  
- [ ] Budget Approval  
- [ ] Timeline Approval  

**Once approved, proceed to Week 1 implementation.** 🚀