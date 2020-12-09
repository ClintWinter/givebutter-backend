Create a Laravel project to manage Contacts (similar to a CRM). You only need to write the backend code, don’t worry about any frontend.

A Contact should have a
- [x] first and last name,
- [x] email addresses,
- [x] and phone numbers.

While a contact should be able to have multiple
- [x] emails
- [x] and phone numbers,

one should be able to be marked as the
- [x] “primary”.

- [x] You must take into account deduplication logic. That is, the consuming client of this API should not need to worry about accidentally creating duplicate contacts. The logic you use to detect duplicates is up to you.

- [x] The only restriction: use just a single table for “contacts”. That is, do not create separate tables for emails and phone numbers.

Your code should be able to...

Contact
- [x] create
- [x] update
- [x] merge two contacts

Email
- [x] create
- [/] update
- [x] delete

Phone numbers
- [x] create
- [/] update
- [x] delete

Please try to showcase your usual coding style and organization as much as possible.
