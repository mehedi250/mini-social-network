import CommentSection from '@/Components/Posts/CommentSection';
import CreatePostForm from '@/Components/Posts/CreatePostForm';
import LikeButton from '@/Components/Posts/LikeButton';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ auth, posts: initialPosts, errors }) {
    const [feedPosts, setFeedPosts] = useState(initialPosts.data);

    const handleNewPost = (newPost) => {
        setFeedPosts([newPost, ...feedPosts]);
    };
    return (
        < div className="py-12" >
            <div className="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

                {errors && Object.keys(errors).length > 0 && (
                    <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong className="font-bold">Oops! </strong>
                        <span className="block sm:inline">Something went wrong with your request.</span>
                        <ul className="mt-2 list-disc list-inside text-sm">
                            {Object.values(errors).map((error, index) => (
                                <li key={index}>{error}</li>
                            ))}
                        </ul>
                    </div>
                )}

                <CreatePostForm user={auth.user} onPostCreated={handleNewPost} />

                {feedPosts.length === 0 ? (
                    <div className="text-center text-gray-500 mt-8">
                        No posts to show right now. Start following people!
                    </div>
                ) : (
                    feedPosts.map((post) => (
                        <div key={post.id} className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                            <div className="flex items-center space-x-3 mb-4">
                                <Link href={`/profile/${post.user?.id}`} className="shrink-0">
                                    <div className="w-12 h-12 rounded-full bg-gray-200 border border-gray-300 overflow-hidden flex items-center justify-center">
                                        <Link href={route('profile.show', post.user.id)}>
                                            {post.user?.profile?.profile_image ? (
                                                <img
                                                    src={post.user?.profile?.profile_image}
                                                    alt={`${post.user?.name}'s avatar`}
                                                    className="w-full h-full object-cover"
                                                />
                                            ) : (
                                                <span className="text-gray-500 font-bold text-lg">
                                                    {post.user.name.charAt(0).toUpperCase()}
                                                </span>
                                            )}
                                        </Link>
                                    </div>
                                </Link>
                                <div>
                                    <Link href={`/profile/${post.user.id}`} className="font-bold text-gray-900 hover:underline">
                                        {post.user.name}
                                    </Link>
                                    <p className="text-xs text-gray-500">
                                        {new Date(post.created_at).toLocaleString([], {
                                            month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
                                        })}
                                    </p>
                                </div>
                            </div>

                            {post.content && (
                                <p className="text-gray-800 whitespace-pre-line mb-4 text-base">
                                    {post.content}
                                </p>
                            )}

                            {post.media_type === 'IMAGE' && post.media_path && (
                                <div className="mb-4 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                    <img
                                        src={`${post.media_path}`}
                                        alt="Post media"
                                        className="w-full max-h-[500px] object-contain"
                                    />
                                </div>
                            )}

                            {post.media_type === 'VIDEO' && post.media_path && (
                                <div className="mb-4 bg-black rounded-lg overflow-hidden border border-gray-800">
                                    <video controls className="w-full max-h-[500px]">
                                        <source src={`${post.media_path}`} />
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            )}

                            {/* Post Footer: Like and Comment Interaction Bar */}
                            <div className="flex items-start gap-6 text-sm text-gray-500 border-t border-gray-100 pt-3 mt-2">
                                <div className="pt-0.5">
                                    <LikeButton post={post} />
                                </div>

                                <div className="flex-1">
                                    <CommentSection post={post} />
                                </div>
                            </div>

                        </div>
                    ))
                )}

                {/* Pagination Placeholder (To be implemented later) */}
                {initialPosts.next_page_url && (
                    <div className="text-center pb-8">
                        <button className="text-blue-500 font-semibold hover:underline">
                            Load More
                        </button>
                    </div>
                )}

            </div>
        </div >

    );
}

Index.layout = page => (
    <AuthenticatedLayout
        header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">News Feed</h2>}
    >
        <Head title="Feed" />
        {page}
    </AuthenticatedLayout>
);