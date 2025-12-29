# Refund Report Feature Walkthrough

This document outlines the implementation of the Refund Report feature and provides instructions for verification and testing.

## Overview

The Refund Report feature allows team members to track refunds with details such as Bill No, Amount, Reason, Courier, Docket No, Payment Mode, and Status. It also tracks who created the refund, who made the error (Error By), and who made the sale (Sales By).

## Features

1.  **Refunds List**: View all refunds with filtering and pagination.
2.  **Add/Edit Refund**: Modal form to create or update refunds.
3.  **Delete Refund**: Remove refunds.
4.  **Settings**:
    - **Refund Statuses**: Manage statuses (e.g., Pending, Approved, Rejected) with colors.
    - **Payment Modes**: Manage payment modes (e.g., Cash, Credit Card).
5.  **Filtering**: Filter by Bill No, Date, Amount, Status, Payment Mode, Error By, and Sales By.

## Menu Locations

- **Main Menu**: "Refunds" item added to the left sidebar (after Sales).
- **Settings Menu**: "Refunds" section added to Settings (after Leads) with "Status" and "Mode of Payment".

## Testing Steps

### 1. Settings Configuration

1.  Navigate to **Settings > Refunds > Status**.
2.  Add a new status (e.g., "Pending", "Processed").
3.  Edit a status (change title or color).
4.  Delete a status (if not default).
5.  Navigate to **Settings > Refunds > Mode of Payment**.
6.  Add a new mode (e.g., "Bank Transfer").
7.  Edit/Delete modes.

### 2. Creating a Refund

1.  Navigate to **Refunds** in the main menu.
2.  Click the **Add** (+) button.
3.  Fill in the form:
    - **Bill No**: Required.
    - **Amount**: Required (Numeric).
    - **Status**: Select from dropdown.
    - **Mode of Payment**: Select from dropdown.
    - **Date**: Defaults to today (backend handles creation date).
    - **Error By / Sales By**: Select team members.
4.  Click **Save**.
5.  Verify the new item appears in the list.

### 3. Filtering

1.  Click the **Filter** icon (top right).
2.  Enter criteria (e.g., Bill No or select a Status).
3.  Click **Apply Filter**.
4.  Verify the list updates to show matching results.
5.  Click **Reset** to clear filters.

### 4. Editing/Deleting

1.  Click the **Edit** (pencil) icon on a row.
2.  Modify details and Save. Verify changes.
3.  Click the **Delete** (trash) icon. Confirm and verify removal.

## Technical Details

- **Database Tables**: `refunds`, `refund_statuses`, `refund_payment_modes`.
- **Controllers**: `App\Http\Controllers\Refunds`, `App\Http\Controllers\Settings\Refunds`.
- **Models**: `Refund`, `RefundStatus`, `RefundPaymentMode`.
- **Repositories**: `RefundRepository` (includes search/filter logic).

## Notes

- Permissions are currently open to all Team members (`is_team`).
- Language keys have been added to `resources/lang/english/lang.php`.
