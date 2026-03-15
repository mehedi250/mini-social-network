import { useState } from 'react';
import axios from 'axios';

export default function CreatePostForm({ user, onPostCreated }) {
    const [content, setContent] = useState('');
    const [media, setMedia] = useState(null);
    const [privacy, setPrivacy] = useState('PUBLIC');
    const [errors, setErrors] = useState({});
    const [isSubmitting, setIsSubmitting] = useState(false);

    const submitPost = async (e) => {
        e.preventDefault();
        setIsSubmitting(true);
        setErrors({});

        const formData = new FormData();
        if (content) formData.append('content', content);
        if (media) formData.append('media', media);
        formData.append('privacy', privacy);

        try {
            const response = await axios.post(route('posts.store'), formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });

            setContent('');
            setMedia(null);
            setPrivacy('PUBLIC');
            document.getElementById('media-upload').value = ''; 

            if (onPostCreated) {
                onPostCreated(response.data.post);
            }
        } catch (error) {
            if (error.response && error.response.status === 422) {
                setErrors(error.response.data.errors);
            } else {
                console.error("An unexpected error occurred:", error);
            }
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div className="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
            <form onSubmit={submitPost}>
                <textarea
                    className={`w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm resize-none ${errors.content ? 'border-red-500' : ''}`}
                    rows="3"
                    placeholder={`What's on your mind, ${user.name}?`}
                    value={content}
                    onChange={(e) => setContent(e.target.value)}
                ></textarea>
                {errors.content && <p className="text-red-500 text-xs mt-1">{errors.content[0]}</p>}

                <div className="flex items-center justify-between mt-4">
                    <div className="flex space-x-4">
                        <div>
                            <label htmlFor="media-upload" className="cursor-pointer text-blue-500 hover:text-blue-600 font-semibold text-sm">
                                📷 Add Image/Video
                            </label>
                            <input
                                id="media-upload"
                                type="file"
                                className="hidden"
                                accept="image/*,video/mp4,video/quicktime,video/x-msvideo"
                                onChange={(e) => setMedia(e.target.files[0])}
                            />
                            {media && <span className="ml-2 text-xs text-gray-500">{media.name}</span>}
                        </div>

                        <select
                            className="text-sm border-gray-300 rounded-md"
                            value={privacy}
                            onChange={(e) => setPrivacy(e.target.value)}
                        >
                            <option value="PUBLIC">🌎 Public</option>
                            <option value="FOLLOWERS">👥 Followers Only</option>
                            <option value="PRIVATE">🔒 Private</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        disabled={isSubmitting}
                        className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md disabled:opacity-50 transition"
                    >
                        {isSubmitting ? 'Posting...' : 'Post'}
                    </button>
                </div>
                
                {errors.media && <p className="text-red-500 text-xs mt-2">{errors.media[0]}</p>}
                {errors.privacy && <p className="text-red-500 text-xs mt-2">{errors.privacy[0]}</p>}
            </form>
        </div>
    );
}