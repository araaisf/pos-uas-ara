<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | ARA POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .bg-luxury { background: radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.3) 0px, transparent 50%), radial-gradient(at 0% 100%, rgba(139, 92, 246, 0.3) 0px, transparent 50%), #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
    </style>
</head>
<body class="h-screen flex items-center justify-center bg-luxury">
    <div class="w-full max-w-md p-8 rounded-[2.5rem] glass shadow-2xl relative">
        <div class="text-center mb-8">
            <h1 class="font-serif text-4xl text-gray-800 mb-2">Join Ara</h1>
            <p class="text-xs text-purple-500 uppercase tracking-[0.3em] font-bold">New Executive</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Full Name" class="w-full bg-white/50 border-none rounded-xl px-5 py-4 focus:ring-2 focus:ring-purple-300 outline-none" required>
            <input type="email" name="email" placeholder="Email Address" class="w-full bg-white/50 border-none rounded-xl px-5 py-4 focus:ring-2 focus:ring-purple-300 outline-none" required>
            <input type="password" name="password" placeholder="Create Password" class="w-full bg-white/50 border-none rounded-xl px-5 py-4 focus:ring-2 focus:ring-purple-300 outline-none" required>
            <button class="w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white py-4 rounded-xl font-bold uppercase tracking-widest shadow-lg hover:shadow-purple-300/50 transition">Create Account</button>
        </form>
        <p class="mt-6 text-center text-sm text-gray-500">Already authorized? <a href="{{ route('login') }}" class="text-pink-600 font-bold hover:underline">Login</a></p>
    </div>
</body>
</html>