@extends('admin.layouts.admin')

@section('title', 'News Management - CrickTracker')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
        <h3 class="text-lg font-bold text-white mb-4">Broadcast Live News Feed</h3>
        <form action="{{ route('admin.news.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="news_title" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Headline Title</label>
                <input type="text" id="news_title" name="title" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
            </div>
            <div>
                <label for="news_content" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Article Body Content</label>
                <textarea id="news_content" name="content" rows="4" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl p-4 text-sm font-medium outline-none focus:border-emerald-500 resize-none"></textarea>
            </div>
            <button type="submit" class="w-full bg-emerald-500 text-slate-950 text-xs font-bold uppercase tracking-wide py-3 rounded-xl hover:bg-emerald-400 transition">Publish News Article</button>
        </form>
    </div>
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <h3 class="text-lg font-bold text-white mb-4">Recent Published Bulletins</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-950 text-slate-400 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4 rounded-l-xl">Headline / Context Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($news ?? [] as $item)
                        <tr class="hover:bg-slate-850/50">
                            <td class="p-4">
                                <div class="text-white font-medium">
                                    {{ data_get($item, 'title') ?? data_get($item, 'TITLE', 'No Title') }}
                                </div>
                                <p class="text-xs text-slate-400 mt-1 line-clamp-2">
                                    {{ data_get($item, 'content') ?? data_get($item, 'CONTENT', 'No content text available.') }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-4 text-center text-slate-500">No dynamic news broadcasts detected.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection