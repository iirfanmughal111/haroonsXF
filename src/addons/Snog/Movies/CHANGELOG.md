**2.2.0 Beta 17:**
- Refactoring and optimization of the addon to reduce the number of requests to API
- Added integration with [OzzModz] Covers
- Added production companies display
- Added TMDb changes tracking and apply job (disabled by default)
- "Movie poster position" option moved to style properties
- Added style properties to change sidebar & poster width
- Added TMDb changes tracking (disabled by default)
- Fix: wrap text on rating button

**2.2.0:**

This is a beta release, take a full backup before installing on a production site.

- General code refactor
- Minimal XF version changed to 2.2
- Removed XenPorta support
- [BREAKING] Add-on was integrated with ForumType and ThreadType system (XF2.2+):
- - "Movie thread forums" add-on option was removed (all previously selected forums in this option will be automatically converted to "TMDb movie forum" type)
- - "Allow mixed content" option was deleted (now can be used "General discussion" forum type with "TMDb Movies threads" type)
- - "Manual update user group" and "Enable manual updates" was deleted, now users with access to change thread type
- Improved performance for crosslink creation (now applies asynchronously with XF:Job)
- Added detailed cast and crew display with person data (name, avatar, character/job)
- Added tab with more videos
- Added movie ratings rebuild tool
- Added Google movie structured data to support rich result for "TMDb movie forum" forum type
- Cached user movie thread count to optimize user criteria
