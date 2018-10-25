```
ORM
                          ╭─ Insert ──────────────
    Collection ───────────┼─ Update ─╮
        │                 ├─ Delete ─┼──────────── needs a condition
        │                 ╰─ Select ─╯
        │ 
        │ 
        ├─ Item 1 ─╮      
        ├─ Item 2 ─┤      ╭─ Insert
        ├─ Item 3 ─┼──────┼─ Update
        ├─ Item 4 ─┤      ├─ Delete
        ╰─ Item N ─╯      ╰─ Select* ─────── can return just one row
```