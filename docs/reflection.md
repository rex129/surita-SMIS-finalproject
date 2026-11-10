# Reflection Report

**Project:** Student Information Management System (SIMS)  **Student:** [Student name]

> Honesty note: the entries below are real events from the build session. Add your own observations from
> your oral defence preparation where marked.

## 1. AI tools used
Claude Code with Claude Sonnet 5.5 (agentic CLI). Headless Chrome was used only to capture screenshots.

## 2. Prompt strategy
Start with one broad instruction so the agent reads all requirement files, then steer with short, single-purpose
prompts (one UI concern each). When a prompt was ambiguous ("no borders on x, only y") the agent stated its
interpretation instead of guessing silently. Audits ("is it still in the instruction?") were used as validation prompts.

## 3. Agent strategy
One agent, several sequential roles (planner, database, Laravel, UI, testing, documentation). See `agent-design.pdf`.
Limitation: roles were not independent reviewers, so the human review step matters more.

## 4. What worked
- Reading the rubric first and building toward its weights (CRUD, API, auth, tests, docs).
- Running the test suite after every change; 32 tests pass.
- Turning repeated UI into Blade components, which made later UI requests one-file changes.
- Verifying with evidence (route list, rendered pages, restore test) rather than trusting "it should work".

## 5. What failed
- Large shell scripts creating many files at once failed with `unexpected EOF`; the work was re-done in smaller
  batches and with the file-writing tool.
- A Python patch script mangled Windows-style backslash namespaces (`\N` escape error); replaced by a direct file write.
- First screenshot attempt wrote files to a wrong path because of backslash handling; fixed with forward slashes.
- The folder rename failed because a `php artisan serve` process held the folder.

## 6. AI mistakes -> correction -> validation (D2 evidence)

| AI suggested | Problem found | Correction applied | Result validated |
|---|---|---|---|
| Migrations generated with identical timestamps (`enrollments` sorted before `subjects`) | Foreign key to a table that did not exist yet | Renamed migration files so `subjects` runs before `enrollments` | `migrate:fresh --seed` succeeds |
| `role` in the User `$fillable` (typical scaffold) | Registration form could set `role=admin` (privilege escalation) | Removed `role` from `$fillable`; set only in seeder; DB default `staff` | `test_user_can_register_as_staff` sends `role=admin` and asserts `staff` |
| `unique` validation rule reused for update | Editing a record without changing its email would fail as "already taken" | `Rule::unique()->ignore($id)` in the shared Form Request | `test_update_allows_keeping_own_unique_values` |
| Test used `$data + ['status' => 'completed']` | PHP `+` keeps the left array's keys, so status stayed `enrolled`; test failed | Switched to `array_merge` | Full suite green |
| Default Laravel pagination view | Unstyled (Tailwind markup without Tailwind) | Custom pagination view + styles; later limited to 5 numbers | Rendered pages 1,2,3,5,8,9 of a 10-page set |
| User said "80rem max-width" | The CSS value was actually 1100px | Reported the difference, removed the cap as requested | Tests pass; layout fills width |
| Search on subjects using `where(...)->orWhere(...)` | `orWhere` outside a group would break any additional filter | Wrapped in a nested closure | Search + paginate pages render |

## 7. Human corrections
The human directed every UI change, chose the interpretation of ambiguous requests, decided how git should be
handled for the final owner (init only, no fabricated commit history), and stopped the server process that blocked the rename.
**[Add your own corrections here.]**

## 8. Lessons learned
1. AI output must be verified with commands and tests, not by reading it.
2. Small, single-purpose prompts are cheaper, safer and easier to review than one giant request.
3. Security defaults (mass assignment, authorisation on every destructive route) are easy for AI to miss; ask for them explicitly and test them.
4. State ambiguity back to the requester instead of silently choosing.
5. Honest documentation matters: this repository does not claim an AI comparison, deployment or commit history that did not happen.
6. **[Add what you would improve with two more weeks: e.g. enrollment capacity limits, grade reports, Postman collection, deployment.]**
