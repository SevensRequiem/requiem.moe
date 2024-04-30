@if (in_array($userId, $adminIds))
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div id="admin">
    <div id="user">
        <span>Logged in as: {{ Auth::user()->username }}</span>
    </div>
        <div id="buttons">
                    <span>[<a href="/admin">Home</a>]</span>
                    <span>[<a href="/admin/post">Make Blog Post</a>]</span>
                    <span>[<a href="/admin/logs">Logs</a>]</span>
                    <span>[<a href="/admin/analytics">Analytics</a>]</span>
            </div>
            <fieldset id="console">
                <legend>console</legend>
                <!--Console will just be a div that is updated with the content of buttons, ajax-->
                <div id="console-content">
                    <<div class="container mt-5">
    <div>
        <div>
        <form method="post" action="./services/blogpost.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="post-date">Date</label>
                    <input type="text" class="form-control" id="post-date" placeholder="Enter date" readonly name="date">
                </div>
                <div class="form-group">
                    <label for="post-title">Title</label>
                    <input type="text" class="form-control" id="post-title" placeholder="Enter title" name="title">
                </div>
                <div class="form-group">
                    <label for="post-author">Author</label>
                    <input type="text" class="form-control" id="post-author" placeholder="Enter author" name="author">
                </div>
                <div class="form-group">
                    <label for="post-quote">Quote</label>
                    <input type="text" class="form-control" id="post-quote" placeholder="Enter quote" name="quote">
                </div>
                <div class="form-group">
                    <label for="post-image">Image</label>
                    <input type="file" class="form-control-file" id="post-image" name="image">
                </div>
                <div class="form-group">
                    <label for="post-hex">Hex Color</label>
                    <input type="color" class="form-control" id="post-hex" value="#EEF" name="hex">
                </div>
                <div class="form-group">
                    <label for="markdown-input">Markdown</label>
                    <textarea id="post-content" name="content" class="form-control" rows="10"></textarea>
                    <small class="form-text text-muted">Write your markdown here</small>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
        <div>
            <h2>Preview</h2>
            <fieldset id="preview">
                <legend>[<span id="postdate"></span>] [<span id="posttitle"></span>] <span id="postauthor"></span> <span
                        id="quote" style="color: #EEF; background-color: ${postHex};"></span></legend>
                <img src="/static/images/" alt="" id="postimage">

                <script>
                document.getElementById('post-image').addEventListener('change', function(e) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('postimage').src = e.target.result;
                    }
                    reader.readAsDataURL(e.target.files[0]);
                });
                </script>
                <span id="postcontent"></span>
            </fieldset>
        </div>
    </div>
</div>
<script>
const postDate = document.getElementById('post-date');
const date = new Date();
const options = { 
  month: '2-digit', 
  day: '2-digit', 
  year: 'numeric', 
  hour: '2-digit', 
  minute: '2-digit', 
  hour12: true, 
  timeZoneName: 'short' 
};
postDate.value = date.toLocaleString('en-US', options);
const postTitle = document.getElementById('post-title');
const postAuthor = document.getElementById('post-author');
const postQuote = document.getElementById('post-quote');
const postImage = document.getElementById('post-image');
const postHex = document.getElementById('post-hex');
const postContent = document.getElementById('post-content');

const previewDate = document.getElementById('postdate');
const previewTitle = document.getElementById('posttitle');
const previewAuthor = document.getElementById('postauthor');
const previewQuote = document.getElementById('quote');
const previewImage = document.getElementById('postimage');
const previewContent = document.getElementById('postcontent');

const updatePreview = () => {
    previewDate.textContent = postDate.value;
    previewTitle.textContent = postTitle.value;
    previewAuthor.textContent = postAuthor.value;
    previewQuote.textContent = postQuote.value;
    previewQuote.style.backgroundColor = postHex.value;
    previewImage.src = `./static/images/${postImage.value}`;
    previewContent.textContent = postContent.value;
};

postDate.addEventListener('input', updatePreview);
postTitle.addEventListener('input', updatePreview);
postAuthor.addEventListener('input', updatePreview);
postQuote.addEventListener('input', updatePreview);
postHex.addEventListener('input', updatePreview);
postImage.addEventListener('input', updatePreview);
postContent.addEventListener('input', updatePreview);
</script>
@endif