import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function ViewProfile({ user, isOwner }) {
    const profile = user.profile || {};

    return (
        <div className="py-8 sm:py-12">
            <div className="max-w-5xl mx-auto sm:px-6 lg:px-8 pb-20">

                {/* Header Section (Cover & Avatar) */}
                <div className="bg-white sm:rounded-2xl shadow-sm overflow-hidden border border-gray-200">

                    {/* Cover Image: Scales from h-48 on mobile to h-64 on desktop */}
                    <div className="h-48 sm:h-64 w-full bg-gray-200 relative">
                        {profile.cover_image ? (
                            <img src={profile.cover_image} alt="Cover" className="w-full h-full object-cover" />
                        ) : (
                            <div className="w-full h-full bg-gradient-to-r from-blue-100 to-blue-50"></div>
                        )}
                    </div>

                    {/* Profile Info Bar */}
                    <div className="px-4 sm:px-8 pb-8">

                        {/* Flex layout now stacks vertically on mobile (flex-col) and sits side-by-side on desktop (sm:flex-row) */}
                        <div className="relative flex flex-col sm:flex-row justify-between items-start sm:items-end -mt-12 sm:-mt-16 mb-4 gap-4 sm:gap-0">

                            {/* Avatar */}
                            <div className="relative h-24 w-24 sm:h-32 sm:w-32 rounded-full border-4 border-white bg-white shadow-sm overflow-hidden shrink-0">
                                {profile.profile_image ? (
                                    <img src={profile.profile_image} alt="Avatar" className="w-full h-full object-cover" />
                                ) : (
                                    <div className="w-full h-full bg-blue-100 flex items-center justify-center text-blue-600 text-4xl sm:text-5xl font-bold">
                                        {user.name.charAt(0)}
                                    </div>
                                )}
                            </div>

                            {/* Edit Button (Conditionally Rendered) */}
                            {isOwner && (
                                <div className="mb-2">
                                    <Link
                                        href={route('profile.edit')}
                                        className="bg-gray-50 hover:bg-gray-100 text-gray-800 font-semibold py-2 px-6 rounded-full transition flex items-center gap-2 border border-gray-300 shadow-sm text-sm"
                                    >
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Edit
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Name, Bio, and Email */}
                        <div>
                            <h1 className="text-2xl sm:text-3xl font-bold text-gray-900">{user.name}</h1>
                            <p className="text-gray-500 font-medium mb-3">{user.email}</p>

                            {profile.bio && (
                                <p className="text-gray-800 max-w-2xl text-base sm:text-lg leading-relaxed">{profile.bio}</p>
                            )}
                        </div>
                    </div>
                </div>

                <div className="mt-6 bg-white sm:rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 className="text-lg sm:text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">About</h2>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-12">

                        <DetailItem icon="💼" label="Profession" value={profile.profession} />
                        <DetailItem icon="🏢" label="Company" value={profile.company} />
                        <DetailItem icon="🎓" label="Education" value={profile.education} />

                        <DetailItem icon="📍" label="Current City" value={profile.current_city} />
                        <DetailItem icon="🏠" label="Home Town" value={profile.home_city} />

                        <DetailItem icon="❤️" label="Relationship" value={profile.relationship_status} />
                        <DetailItem
                            icon="👤"
                            label="Gender"
                            value={profile.gender !== 'PREFER_NOT_TO_SAY' ? profile.gender?.toLowerCase() : null}
                            capitalize
                        />
                        <DetailItem
                            icon="🎂"
                            label="Birthday"
                            value={
                                profile.date_of_birth
                                    ? (() => {
                                        const d = new Date(profile.date_of_birth);
                                        return `${d.getDate()} ${d.toLocaleString('default', { month: 'long' })}, ${d.getFullYear()}`;
                                    })()
                                    : null
                            }
                        />

                        {profile.website && (
                            <div className="flex flex-col">
                                <span className="text-sm font-medium text-gray-500 mb-1 flex items-center gap-2">🔗 Website</span>
                                <a href={profile.website} target="_blank" rel="noreferrer" className="text-blue-600 hover:underline font-medium break-all">
                                    {profile.website}
                                </a>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}

function DetailItem({ icon, label, value, capitalize = false }) {
    if (!value) return null;

    return (
        <div className="flex flex-col">
            <span className="text-sm font-medium text-gray-500 mb-1 flex items-center gap-2">
                <span>{icon}</span> {label}
            </span>
            <span className={`text-gray-900 font-medium ${capitalize ? 'capitalize' : ''}`}>
                {value}
            </span>
        </div>
    );
}

ViewProfile.layout = page => (
    <AuthenticatedLayout
        header={
            <h2 className="text-xl font-semibold leading-tight text-gray-800">
                Profile
            </h2>
        }
    >
        <Head title="Profile" />
        {page}
    </AuthenticatedLayout>
);