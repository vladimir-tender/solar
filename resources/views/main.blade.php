@extends('layouts.layout')


@section('content')

    {{--@foreach($comments as $comment)
        <div class="col-md-6">
            {{ $comment['comment'] }}<br>
            --}}{{--<a href="/comment/{{$comment['id']}}">Link</a>--}}{{--
            --}}{{--<a href="{{ route('comment', ['id' => $comment->id]) }}"></a>--}}{{--
        </div>
    @endforeach--}}
    <h2>Comment tree. Data via api</h2>



    <div class="comments-container">

    </div>
@endsection



@section('bottom_javascripts')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var prototype = "<div data-comment-id='__id__' data-parent='__parent_id__'><div id='comment_body___id__'>__comment__</div></div>" +
                "<div>" +
                "<button class='btn btn-xs btn-success' id='answer___id__'>answer</button>" +
                "<button class='btn btn-xs btn-primary' id='edit___id__'>edit</button>" +
                "<button class='btn btn-xs btn-danger' id='remove___id__'>remove</button></div>" +
                "<div class='textarea_block' id='edit_block___id__' style='display: none'>" +
                "<textarea class='form-control' id='__id__'></textarea>" +
                "<button class='btn btn-sm btn-primary edit-btn' style='display: none' id='btn_edit___id__'>Edit</button>" +
                "<button class='btn btn-sm btn-success add-btn' style='display: none' id='btn_add___id__' data-parent='__parent_id__'>Add</button>" +
                "</div>";
            var html = "";

            buildComments();

            function buildComments() {
                html = '<button class="btn btn-sm btn-success" id="answer_0">Add comment</button>\n' +
                    '    <div class=\'textarea_block\' id=\'edit_block_0\' style=\'display: none\'>\n' +
                    '        <textarea class=\'form-control\' id=\'0\'></textarea>\n' +
                    '        <button class=\'btn btn-sm btn-success add-btn\' id=\'btn_add_0\' >Add</button>\n' +
                    '    </div>';
                $('textarea[id=0]').val('');
                $.ajax({
                    type: "GET",
                    url: '/comments/ajax',
                    success: function (json) {
                        //console.log(json);
                        html = buildTree(json);
                        $('.comments-container').empty().html(html);
                        userEvents();
                    }
                })
            }

            function buildTree(array) {
                html += "<ul class='list-group'>";
                array.forEach(function (comment, index) {
                    //console.log(comment);
                    html += "<li class='list-group-item'>";
                    html += createComment(comment);
                    html += "</li>";
                    if (comment.childs.length > 0) {
                        html += "<li class='list-group-item'>";
                        buildTree(comment.childs);
                        html += "</li>";
                    }
                });
                html += "</ul>";
                return html;
            }

            function createComment(comment) {
                var new_comment = prototype;
                new_comment = new_comment.replace(/__id__/g, comment.id);
                new_comment = new_comment.replace(/__parent_id__/g, comment.parent_id);
                new_comment = new_comment.replace(/__comment__/g, comment.comment);
                return new_comment;
            }

            function userEvents() {
                $('button[id^=answer_]').click(function () {
                    var id = getCommentId(this.id);

                    $('.textarea_block').hide();
                    $('.edit-btn').hide();
                    $('.add-btn').hide();

                    $('div[id=edit_block_' + id + ']').show();
                    $('button[id=btn_add_' + id + ']').show();

                    $('textarea[id=' + id + ']').val('').focus();


                });

                $('button[id^=edit_]').click(function () {
                    var id = getCommentId(this.id);

                    $.ajax({
                        type: "GET",
                        url: '/get/comment/' + id,
                        success: function (comment) {
                            //console.log(json);
                            $('textarea[id=' + id + ']').val(comment.comment);
                        }
                    });


                    $('.textarea_block').hide();
                    $('.edit-btn').hide();
                    $('.add-btn').hide();

                    $('div[id=edit_block_' + id + ']').show();
                    $('button[id=btn_edit_' + id + ']').show();
                    $('textarea[id=' + id + ']').focus();
                });

                $('button[id^=remove_]').click(function () {
                    if (confirm('Are you sure?')) {
                        var id = getCommentId(this.id);

                        $.ajax({
                            url: '/comment/remove/'+id,
                            type: 'post',
                            //data: {'id': id},

                            success: function (answer) {
                                //alert(answer);
                                if (answer == 'true') {
                                    buildComments();
                                } else {
                                    alert(answer);
                                }
                            }
                        });
                        //$('div[data-comment-id=' + id + ']').parent().remove();
                    }

                });

                //add or edit events

                $('button[id^=btn_add_]').click(function () {
                    var id = $(this).attr('id').split('_')[2];
                    //var parent_id = $(this).attr('data-parent');
                    var text = $('textarea[id=' + id + ']').val();
                    if (text.length == 0) {
                        alert('Enter text');
                        $('textarea[id=' + id + ']').focus();
                    } else {
                        $.ajax({
                            url: '/comment/add',
                            type: 'post',
                            data: {'parent_id': id, 'comment': text },

                            success: function (answer) {
                                //alert(answer);
                                if (answer == 'true') {
                                    $('textarea[id=' + id + ']').val('');
                                    $('div[id=edit_block_'+id+']').hide();
                                    buildComments();
                                } else {
                                    alert(answer);
                                }
                            }
                        });
                    }
                });

                $('button[id^=btn_edit_]').click(function () {
                    var id = $(this).attr('id').split('_')[2];
                    //var parent_id = $(this).attr('data-parent');
                    var text = $('textarea[id=' + id + ']').val();
                    if (text.length == 0) {
                        alert('Enter text');
                        $('textarea[id=' + id + ']').focus();
                    } else {
                        $.ajax({
                            url: '/comment/edit',
                            type: 'post',
                            data: {'id': id, 'comment': text },

                            success: function (answer) {
                                //alert(answer);
                                if (answer == 'true') {
                                    $('textarea[id=' + id + ']').val('');
                                    $('div[id=edit_block_'+id+']').hide();
                                    buildComments();
                                } else {
                                    alert(answer);
                                }
                            }
                        });
                    }
                });
            }

            function getCommentId(id) {
                return id.split('_')[1];
            }
        });
    </script>
@endsection