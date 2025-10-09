# Lucky Draw Winner Selection Implementation

## Overview
This document outlines the implementation of the Lucky Draw contestant viewing and winner selection functionality with conditional rewards.

## Features Implemented

### 1. Contestants View Page
- **Route**: `/admin/lucky-withdraw/contestants`
- **Purpose**: Display all users who have entered the current lucky draw round
- **Features**:
  - List of all approved contestants for the current round
  - Search functionality by name or email
  - Display of eligibility status for each contestant
  - Shows active plans count and Plan 3 subscription history
  - Preview of reward type each contestant will receive if selected

### 2. Winner Selection Logic
Two methods of winner selection:

#### A. Random Selection
- **Route**: `POST /admin/lucky-withdraw/select-winner`
- Randomly selects a winner from all approved contestants

#### B. Manual Selection
- **Route**: `POST /admin/lucky-withdraw/{entry}/select-as-winner`
- Admin can select a specific contestant as the winner from the contestants list

### 3. Conditional Reward System

When a winner is selected, the system checks two conditions:

#### Condition 1: Plan 3 Subscription History
- Check if the user has ever subscribed to Plan 3 (investment_plan_id = 3)
- Includes both current and past subscriptions

#### Condition 2: Active Plans Count
- Count the number of active plans (not expired and not soft-deleted)
- Maximum limit is 2 active plans

#### Reward Distribution:

**If user meets BOTH conditions** (never subscribed Plan 3 AND has less than 2 active plans):
- ✅ Subscribe user to Plan 3
- Duration: Plan 3's plan_days value
- Notification: "Congratulations! You have been subscribed to Plan 3 for X days."

**If user does NOT meet the conditions** (already subscribed Plan 3 OR has 2 active plans):
- 💰 Add Rs. 7000 to Main Wallet as bonus
- Also updates legacy UserAccount for backward compatibility
- Notification: "Congratulations! Rs. 7000 has been added to your Main Wallet as bonus."

## Files Modified

### 1. Controller: `app/Http/Controllers/LuckyWithdrawController.php`
Added/Modified methods:
- `contestants()` - Display list of contestants for current round
- `selectWinner()` - Random winner selection (updated logic)
- `selectSpecificWinner()` - Manual winner selection
- `processWinnerReward()` - Private method to handle reward logic based on conditions

### 2. Routes: `routes/web.php`
Added routes:
```php
Route::get('/admin/lucky-withdraw/contestants', 'contestants')
Route::post('/admin/lucky-withdraw/{luckyWithdrawEntry}/select-as-winner', 'selectSpecificWinner')
```

### 3. Views
Created: `resources/views/backend/admin/lucky-withdraw/contestants.blade.php`
- Full-featured contestants list page
- Shows eligibility and reward preview for each contestant
- Winner selection buttons (if no winner selected yet)
- Winner information display (if winner already selected)

Modified: `resources/views/backend/admin/lucky-withdraw/index.blade.php`
- Added "View Contestants & Select Winner" button
- Updated "Select Winner" to "Random Winner Selection" for clarity

Modified: `resources/views/layout/sidebar.blade.php`
- **Added "Lucky Draw" menu item for Admin** (links to `/admin/lucky-withdraw`)
  - Located after "Withdraw Requests" and before "Settings"
  - Icon: Trophy (fa-trophy)
- **Added "Lucky Draw" menu item for Users** (links to `/lucky-withdraw`)
  - Located after "Withdrawals" and before "Support & Help"
  - Icon: Trophy (fa-trophy)

## Validation & Safety Features

1. **One Winner Per Round**: Once a winner is selected, no other winner can be selected for that round
2. **Eligibility Checks**: Only approved and active entries can be selected as winners
3. **Current Round Check**: Only entries from the current round are eligible
4. **Confirmation Dialogs**: JavaScript confirmation before selecting winner (prevents accidental selection)
5. **Transaction Safety**: All database operations wrapped in transactions
6. **Error Logging**: All errors logged for debugging
7. **Role-Based Access Control**: 
   - Admin routes protected by admin middleware (only ADMIN and SUPERADMIN can access)
   - Regular users cannot access admin lucky withdraw pages
   - Admins are redirected to admin panel when navigating back from entry details
   - Users are redirected to user lucky withdraw page when navigating back
8. **Middleware Protection**: All admin routes wrapped in `admin` middleware group

## Database Tables Used

- `lucky_withdraw_entries` - Contest entries
- `user_subscriptions` - User's plan subscriptions
- `investment_plans` - Plan details (Plan 3)
- `user_wallets` - User's wallet balances
- `user_accounts` - Legacy account system (for backward compatibility)
- `user_notifications` - Winner notifications

## User Experience Flow

### Admin Flow:
1. Navigate to Lucky Withdraw Management (`/admin/lucky-withdraw`)
2. Click "View Contestants & Select Winner" button
3. View list of all contestants with eligibility status
4. Choose to:
   - Select specific contestant by clicking "Select Winner" button on their row
   - Go back and use "Random Winner Selection" button
5. Confirm the action
6. System automatically:
   - Marks the winner
   - Applies appropriate reward based on conditions
   - Sends notification to winner
   - Marks all entries as completed

### Winner Flow:
1. Receives notification about winning
2. Automatically receives either:
   - Plan 3 subscription (can start earning immediately)
   - OR Rs. 7000 in Main Wallet (can withdraw or use for plan purchase)

## Error Handling

- Invalid entry selection: User-friendly error message
- Database errors: Rollback transaction and log error
- Missing Plan 3: Throws exception with clear message
- Winner already selected: Prevents duplicate selection

## Testing Checklist

- [ ] View contestants page loads correctly
- [ ] Search functionality works
- [ ] Eligibility status displays correctly
- [ ] Manual winner selection works
- [ ] Random winner selection works
- [ ] Plan 3 subscription granted when conditions met
- [ ] Rs. 7000 bonus added when conditions not met
- [ ] Winner notification sent correctly
- [ ] Cannot select second winner for same round
- [ ] Transaction rollback on error

## Future Enhancements (Optional)

1. Export contestants list to CSV
2. Filter contestants by eligibility type
3. View previous round winners
4. Email notification to winner (in addition to in-app notification)
5. Admin notes on winner selection reason

## Recent Features

### Round Management Controls (Latest)
**Feature**: Admin can now manually end a draw and start a new round without waiting for the automatic 15-day cycle.

**Implementation**:
1. **Added Controller Methods** (`LuckyWithdrawController.php`):
   - `endCurrentRound()`: Ends the current round manually
     - Validates that a winner has been selected
     - Marks all active entries as 'completed'
     - Updates round end date to today
     - Logs the action
   
   - `startNewRound()`: Starts a new round manually
     - Checks that no active entries exist
     - Calculates new round dates (15 days from today)
     - Sends notifications to all users about the new round
     - Logs the action

2. **Added Routes** (`routes/web.php`):
   ```php
   Route::post('/admin/lucky-withdraw/end-round', 'endCurrentRound')
   Route::post('/admin/lucky-withdraw/start-round', 'startNewRound')
   ```

3. **Updated Admin View** (`resources/views/backend/admin/lucky-withdraw/index.blade.php`):
   - Added "Round Management Controls" section with yellow warning border
   - **End Current Round** button:
     - Enabled only when winner is selected
     - Shows helpful status messages
     - Requires confirmation before execution
   - **Start New Round** button:
     - Enabled only when no active entries exist
     - Notifies all users automatically
     - Requires confirmation before execution
   - Added informational alert explaining the feature

**Workflow**:
1. Admin selects winner for current round
2. Admin clicks "End Current Round" → All entries marked as completed
3. Admin clicks "Start New Round" → New 15-day round begins, users notified
4. Users can now enter the new round

**Safety Features**:
- Cannot end round without selecting winner first
- Cannot start new round if active entries exist
- Confirmation dialogs prevent accidental actions
- All actions logged for audit trail
- Users automatically notified of new rounds

**Result**: 
- ✅ Admins have full control over round timing
- ✅ No need to wait for automatic 15-day cycle
- ✅ Can run multiple rounds faster if needed
- ✅ All existing functionality preserved
- ✅ Current page content remains intact

### Navigation and Security Fix
**Issue**: When clicking "Back to Lucky Withdraw" from entry detail page, admins were being redirected to user pages and vice versa.

**Solution**:
1. **Updated Controller** (`LuckyWithdrawController::show`):
   - Now passes `$isAdmin` variable to the view
   - Determines if current user is admin or regular user

2. **Updated View** (`resources/views/backend/user/lucky-withdraw/show.blade.php`):
   - Added conditional back button based on user role
   - Admins see "Back to Admin Panel" → routes to `admin.lucky-withdraw.index`
   - Users see "Back to Lucky Withdraw" → routes to `lucky-withdraw.index`

3. **Fixed AdminMiddleware** (`app/Http/Middleware/AdminMiddleware.php`):
   - Changed from checking non-existent `is_admin` property
   - Now properly checks `role` field for 'ADMIN' or 'SUPERADMIN'

4. **Added Route Protection** (`routes/web.php`):
   - All admin lucky withdraw routes now wrapped in `admin` middleware group
   - Prevents unauthorized access to admin pages
   - Returns 403 error if non-admin tries to access admin routes

**Result**: 
- ✅ Admins stay in admin panel
- ✅ Users stay in user panel
- ✅ No cross-contamination of routes
- ✅ Proper access control enforced

## Support

For issues or questions, check:
- Laravel logs: `storage/logs/laravel.log`
- Error prefix: "Lucky Draw - "
- Database tables mentioned above

