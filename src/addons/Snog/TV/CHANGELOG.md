**2.2.0 Release Candidate 9:**
- Refactoring and optimization of the addon to reduce the number of requests to API
- Added integration with [OzzModz] Covers
- Added production companies & networks display
- Added TV show status filter in thread list
- "TV show poster position" option moved to style properties
- Added TMDb changes tracking (disabled by default)
- Added button to update TV episode info
- Added general discussion forum type support
- Fix: wrap text on rating button

**2.2.0:**

This is a beta release, take a full backup before installing on a production site.

- General code refactor
- Minimal XF version changed to 2.2
- [BREAKING] Add-on was integrated with ForumType and ThreadType system (XF2.2+):
- - "TV thread forums" add-on option was removed (all previously selected forums in this option will be automatically converted to "TMDb TV forum" type)
- - "Allow mixed content" option was deleted (now can be used "General discussion" forum type with "TMDb TV threads" type)
- - "Enable manual updates" options was deleted, now users with access to change thread type
- Improved performance for crosslink creation (now applies asynchronously with XF:Job)
- Added detailed cast and crew display with person data (name, avatar, character/job)
- Added tab with more videos
- Added option to replace template for search results to display detailed TV info
- Fixed broken rating for individual TV shows forums
- Added TV/TV forum ratings rebuild tool
- Added Google structured data to support rich result for "TMDb TV forum" forum type
- Cached user TV thread count to optimize user criteria
- Added thread list sorting by TV season and episode number
