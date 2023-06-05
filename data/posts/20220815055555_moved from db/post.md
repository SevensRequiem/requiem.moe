ah, i have finally made the blog system non-db dependant, and also it ***supports markdown***!
# H1
## H2
### H3
**bold text**
*italicized text*
# ahh another bug :D
img elements still showing, even tho there is no src T_T
ahhh it will be a simple fix, such as:
```
 if ($row['article_image'] == NULL){
				echo 'posts without images';
        } else {
      echo 'posts with images';
```
well, thats how i did it with the DB. maybe, `$json['image']; == NULL` would work instead. idk, thats something for tomorrow me. i am dog ass tired