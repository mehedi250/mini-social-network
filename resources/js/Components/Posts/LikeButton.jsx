import { useState } from 'react';
import axios from 'axios';

export default function LikeButton({ post }) {
    const [isLiked, setIsLiked] = useState(post.is_liked);
    const [likesCount, setLikesCount] = useState(post.likes_count || 0);
    const [isLoading, setIsLoading] = useState(false);

    const toggleLike = async () => {
        if (isLoading) return;

        setIsLiked(!isLiked);
        setIsLoading(true);

        try {
            const response = await axios.post(route('posts.like', post.id));
            setLikesCount(response.data.data.likes_count);
        } catch (error) {
            console.error("Error toggling like", error);
            setIsLiked(!isLiked);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <button
            onClick={() => toggleLike()}
            className={`flex items-center space-x-1 transition font-medium ${isLiked ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600'}`}
        >
            <svg
                className="w-5 h-5"
                fill={isLiked ? "currentColor" : "none"}
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
            </svg>
            <span>{likesCount || "0"} {likesCount === 1 ? 'Like' : 'Likes'}</span>
        </button>
    );
}