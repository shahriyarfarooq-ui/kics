# ERP Login Authentication Request

## Purpose
This document explains the requirement to enable ERP-based login for the KICS staff portal so staff members can sign in with their ERP credentials and access only their own profile and related details.

## Current Situation
The KICS admin/staff portal is built on Laravel authentication. At the moment, it validates users against the local `users` table rather than the ERP system. This means ERP staff cannot directly sign in using ERP credentials unless the ERP authentication is integrated or the local staff accounts are linked to ERP identities.

## Requested Access
We request ERP authentication support for the staff portal so that:
- staff can log in with their ERP credentials
- access is limited to their own profile and authorized details
- staff can edit only their own data
- admin users continue to manage shared content and portal settings

## Technical Requirement
The portal should support one of the following:
1. ERP-based authentication via ERP login service / SSO / API validation, or
2. Secure synchronization of ERP staff identities into the portal so each staff member is matched to the correct profile record.

## Desired Outcome
After implementation:
- staff can sign in using ERP login details
- each staff member sees only their own profile information
- profile editing remains restricted to the authorized user
- no staff member can view other staff records by default

---

# Email Draft

Subject: Request for ERP Login Authentication for Staff Portal

Dear [ERP/IT Team],

I hope you are well.

We are requesting ERP-based authentication for the KICS staff portal so that staff members can log in using their existing ERP credentials instead of separate local portal credentials.

The current portal is used by staff to access and update their own profile information and related details. We need the login flow to validate staff users against the ERP system so that only the authorized staff member can access their specific record and edit their own information.

This will help ensure:
- secure staff login through ERP credentials
- user-specific access control
- restricted profile visibility
- better alignment with the ERP identity system already in use by the organization

Kindly let us know if the ERP login integration or user mapping support can be enabled for the KICS portal.

If needed, we can share the portal route and authentication flow details for implementation and testing.

Thank you for your support.

Best regards,
[Your Name]
[Your Designation / Department]
[Contact Information]
[Email Address]

---

## Short Version for Quick Sending

Subject: Request for ERP Authentication for Staff Portal

Dear [Name],

We would like to request ERP-based authentication for the KICS staff portal. Staff members should be able to log in using their ERP credentials and access only their own profile details for editing and review.

The portal is currently using local authentication, and we need this integrated with ERP user credentials so that access is restricted to the authorized staff member and aligned with existing ERP account identity.

Please let us know if ERP login integration or user mapping can be enabled for this system.

Thank you.

Best regards,
[Your Name]
