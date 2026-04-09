import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import axios from 'axios';
import { useState } from 'react';
import toast from 'react-hot-toast';

export default function EditProfile({ user }) {
    const profile = user.profile || {};

    const { data, setData, setError, clearErrors, errors } = useForm({
        bio: profile.bio || '',
        profession: profile.profession || '',
        company: profile.company || '',
        education: profile.education || '',
        date_of_birth: profile.date_of_birth || '',
        gender: profile.gender || 'PREFER_NOT_TO_SAY',
        relationship_status: profile.relationship_status || '',
        home_city: profile.home_city || '',
        current_city: profile.current_city || '',
        website: profile.website || '',
        profile_image: null,
        cover_image: null,
    });

    const [isSubmitting, setIsSubmitting] = useState(false);
    const [avatarPreview, setAvatarPreview] = useState(profile.profile_image);
    const [coverPreview, setCoverPreview] = useState(profile.cover_image);

    const handleImageChange = (e, field, setPreview) => {
        const file = e.target.files[0];
        if (file) {
            setData(field, file);
            setPreview(URL.createObjectURL(file));
        }
    };

    const submit = async (e) => {
        e.preventDefault();
        if (isSubmitting) return;

        setIsSubmitting(true);
        clearErrors();

        const formData = new FormData();
        Object.keys(data).forEach(key => {
            if (key !== 'profile_image' && key !== 'cover_image') {
                formData.append(key, data[key] || '');
            }
        });

        if (data.profile_image instanceof File) formData.append('profile_image', data.profile_image);
        if (data.cover_image instanceof File) formData.append('cover_image', data.cover_image);

        const toastId = toast.loading('Saving profile...');

        try {
            const response = await axios.put(route('profile.update'), formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            });
            toast.success(response.data.message, { id: toastId });

        } catch (error) {
            const errorMessage = error.response?.data?.message || 'An unexpected error occurred.';
            toast.error(errorMessage, { id: toastId });

            if (error.response && error.response.status === 422 && error.response.data.errors) {
                const laravelErrors = error.response.data.errors;
                Object.keys(laravelErrors).forEach(field => {
                    setError(field, laravelErrors[field][0]);
                });
            }
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div className="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-sm mt-10 border border-gray-100 mb-20">
            <h2 className="text-2xl font-bold text-gray-900 mb-6">Edit Your Profile</h2>

            <form onSubmit={submit} className="space-y-8" encType="multipart/form-data">

                {/* Visuals Section (Cover & Avatar) */}
                <div className="space-y-4">
                    {/* Cover Image */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">Cover Photo</label>
                        <div className="relative h-48 w-full bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex items-center justify-center group">
                            {coverPreview ? (
                                <img src={coverPreview} className="w-full h-full object-cover" alt="Cover" />
                            ) : <span className="text-gray-400">No cover image</span>}

                            <label className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition cursor-pointer flex items-center justify-center">
                                <span className="opacity-0 group-hover:opacity-100 text-white font-semibold bg-black bg-opacity-50 px-4 py-2 rounded-full">Change Cover</span>
                                <input type="file" className="hidden" accept="image/*" onChange={e => handleImageChange(e, 'cover_image', setCoverPreview)} />
                            </label>
                        </div>
                        {errors.cover_image && <p className="text-red-500 text-sm mt-1">{errors.cover_image}</p>}
                    </div>

                    {/* Profile Image */}
                    <div className="flex items-center space-x-6">
                        <div className="relative h-24 w-24 rounded-full bg-blue-100 border-4 border-white shadow-sm overflow-hidden group">
                            {avatarPreview ? (
                                <img src={avatarPreview} className="w-full h-full object-cover" alt="Avatar" />
                            ) : (
                                <div className="w-full h-full flex items-center justify-center text-blue-600 text-3xl font-bold">{user.name.charAt(0)}</div>
                            )}
                            <label className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition cursor-pointer flex items-center justify-center">
                                <input type="file" className="hidden" accept="image/*" onChange={e => handleImageChange(e, 'profile_image', setAvatarPreview)} />
                            </label>
                        </div>
                        <div className="text-sm text-gray-500">Click your avatar to upload a new profile picture.</div>
                        {errors.profile_image && <p className="text-red-500 text-sm">{errors.profile_image}</p>}
                    </div>
                </div>

                {/* Bio (Full Width) */}
                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">Bio (Max 120 chars)</label>
                    <textarea value={data.bio} onChange={e => setData('bio', e.target.value)} rows="3" maxLength="120" className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200" placeholder="A short bio..."></textarea>
                    {errors.bio && <p className="text-red-500 text-sm">{errors.bio}</p>}
                </div>

                {/* Two Column Data Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Profession</label>
                        <input type="text" value={data.profession} onChange={e => setData('profession', e.target.value)} className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Company</label>
                        <input type="text" value={data.company} onChange={e => setData('company', e.target.value)} className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Education</label>
                        <input type="text" value={data.education} onChange={e => setData('education', e.target.value)} className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="url" value={data.website} onChange={e => setData('website', e.target.value)} placeholder="https://" className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Current City</label>
                        <input type="text" value={data.current_city} onChange={e => setData('current_city', e.target.value)} className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Home Town</label>
                        <input type="text" value={data.home_city} onChange={e => setData('home_city', e.target.value)} className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" value={data.date_of_birth} onChange={e => setData('date_of_birth', e.target.value)} className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200" />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select value={data.gender} onChange={e => setData('gender', e.target.value)} className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200">
                            <option value="PREFER_NOT_TO_SAY">Prefer not to say</option>
                            <option value="MALE">Male</option>
                            <option value="FEMALE">Female</option>
                            <option value="OTHER">Other</option>
                        </select>
                    </div>
                    <div className="md:col-span-2">
                        <label className="block text-sm font-medium text-gray-700 mb-1">Relationship Status</label>
                        <input type="text" value={data.relationship_status} onChange={e => setData('relationship_status', e.target.value)} placeholder="Single, Married, It's complicated..." className="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-200" />
                    </div>
                </div>

                <div className="flex justify-end pt-4 border-t border-gray-100">
                    <button
                        type="submit"
                        disabled={isSubmitting}
                        className="bg-blue-600 text-white px-8 py-3 rounded-full font-semibold disabled:opacity-50 hover:bg-blue-700 transition"
                    >
                        {isSubmitting ? 'Saving...' : 'Save Profile Settings'}
                    </button>
                </div>
            </form>
        </div>
    );
}

EditProfile.layout = page => {
    const user = page.props.user;
    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-4">
                    <Link
                        href={route('profile.show', user.id)}
                        className="group flex items-center text-sm font-semibold text-gray-500 hover:text-blue-600 transition"
                    >
                        <svg
                            className="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </Link>

                    <h2 className="text-xl font-semibold leading-tight text-gray-800 border-l border-gray-300 pl-4">
                        Edit Your Profile
                    </h2>
                </div>
            }
        >
            <Head title="Edit Profile" />
            {page}
        </AuthenticatedLayout>
    );
}
