import { useState } from 'react';
import axios from 'axios';
import { Link } from '@inertiajs/react';

export default function CommentSection({ post }) {
    const [isOpen, setIsOpen] = useState(false);
    const [comments, setComments] = useState([]);
    const [newComment, setNewComment] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [commentCount, setCommentCount] = useState(post.comments_count || 0);

    const toggleComments = async () => {
        setIsOpen(!isOpen);
        if (!isOpen && comments.length === 0) {
            setIsLoading(true);
            try {
                const response = await axios.get(route('posts.comments.index', post.id));
                setComments(response.data.data);
            } catch (error) {
                console.error("Error fetching comments", error);
            } finally {
                setIsLoading(false);
            }
        }
    };

    const submitComment = async (e) => {
        e.preventDefault();
        if (!newComment.trim() || isSubmitting) return;

        setIsSubmitting(true);
        try {
            const response = await axios.post(route('posts.comments.store', post.id), {
                content: newComment
            });
            setComments([response.data.data, ...comments]);
            setNewComment('');
            setCommentCount(commentCount + 1);
        } catch (error) {
            console.error("Error posting comment", error);
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div className="w-full">
            {/* The Comment Toggle Button */}
            <button
                onClick={toggleComments}
                className="flex items-center space-x-1 text-gray-500 hover:text-blue-600 transition font-medium"
            >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>{commentCount} {commentCount === 1 ? 'Comment' : 'Comments'}</span>
            </button>

            {/* The Dropdown Section */}
            {isOpen && (
                <div className="mt-4 pt-4 border-t border-gray-100">
                    {/* Input Form */}
                    <form onSubmit={submitComment} className="flex gap-2 mb-4">
                        <input
                            type="text"
                            value={newComment}
                            onChange={(e) => setNewComment(e.target.value)}
                            placeholder="Write a comment..."
                            className="flex-1 rounded-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm px-4 py-2"
                        />
                        <button
                            type="submit"
                            disabled={isSubmitting || !newComment.trim()}
                            className="bg-blue-600 text-white px-4 py-2 rounded-full text-sm font-semibold disabled:opacity-50 hover:bg-blue-700 transition"
                        >
                            Post
                        </button>
                    </form>

                    {/* Comments List */}
                    {isLoading ? (
                        <div className="text-center text-gray-400 text-sm py-2">Loading comments...</div>
                    ) : (
                        <div className="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            {comments.map(comment => (
                                <div key={comment.id} className="bg-gray-50 p-3 rounded-lg flex gap-3">
                                    <Link href={route('profile.show', comment.user.id)}>
                                        {comment.user?.profile?.profile_image ? (
                                            <img
                                                src={comment.user.profile.profile_image}
                                                alt={comment.user?.name || 'User'}
                                                className="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-gray-200"
                                            />
                                        ) : (
                                            <div className="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center font-bold text-blue-600 text-sm">
                                                {comment.user?.name?.charAt(0) || 'U'}
                                            </div>
                                        )}
                                    </Link>

                                    <div>
                                        <div className="font-semibold text-sm text-gray-900">{comment.user?.name}</div>
                                        <div className="text-sm text-gray-700 mt-1">{comment.content}</div>
                                    </div>
                                </div>
                            ))}
                            {comments.length === 0 && (
                                <div className="text-center text-gray-500 text-sm py-2">No comments yet. Be the first!</div>
                            )}
                        </div>
                    )}
                </div>
            )}
        </div>
    );
}