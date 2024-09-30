### IMPORTANT NOTES
- In a newly installed server, ENABLE `extensions=sockets` in php.ini
- In `LeaveImageAttachments` table, update the `HexImage` column to [text]

### GENERATE MODELS FROM TABLE
- php artisan infyom:scaffold Post --fromTable --table=posts --connection=server_name

### NEW TABLES
- Posts
- PostReactions

### NEW PACKAGES

### COLORS
- Red - #e03822
- Green - #3a9971