import sqlite3
import uuid

# Connect to the database
conn = sqlite3.connect('chat.db')
c = conn.cursor()

# Select rows with null/empty uuid
c.execute("SELECT * FROM messages WHERE uuid IS NULL OR uuid = ''")

# Update uuid for each row
for row in c.fetchall():
    new_uuid = 'msg_' + str(uuid.uuid4()).replace('-', '')[:6]
    c.execute("UPDATE messages SET uuid = ? WHERE id = ?", (new_uuid, row[0]))

# Commit changes and close connection
conn.commit()
conn.close()