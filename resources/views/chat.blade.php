<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Chat en tiempo real | Laravel + Reverb</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: linear-gradient(120deg, #f8fafc 0%, #e0e7ef 100%);
      margin: 0;
      min-height: 100vh;
    }
    .chat-container {
      max-width: 480px;
      margin: 40px auto;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 4px 24px 0 #0001;
      padding: 32px 28px 24px 28px;
    }
    .chat-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 18px;
    }
    .chat-header h1 {
      font-size: 1.5rem;
      font-weight: 600;
      margin: 0;
      color: #2d3748;
    }
    .messages {
      max-height: 340px;
      overflow-y: auto;
      margin-bottom: 18px;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      background: #f9fafb;
      padding: 16px 12px;
    }
    .msg {
      margin-bottom: 12px;
      padding-bottom: 8px;
      border-bottom: 1px solid #e2e8f0;
    }
    .msg:last-child {
      border-bottom: none;
    }
    .msg-user {
      font-weight: 600;
      color: #2563eb;
    }
    .msg-content {
      margin: 2px 0 0 0;
      color: #222;
    }
    .msg-meta {
      font-size: 11px;
      color: #64748b;
      margin-top: 2px;
    }
    .chat-form {
      display: flex;
      gap: 8px;
      margin-top: 8px;
    }
    .chat-form input {
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      font-size: 1rem;
      outline: none;
      transition: border 0.2s;
    }
    .chat-form input:focus {
      border: 1.5px solid #2563eb;
    }
    .chat-form button {
      background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 0 22px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }
    .chat-form button:hover {
      background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%);
    }
  </style>
</head>
<body>
<div class="chat-container">
  <div class="chat-header">
    <span style="font-size:1.7rem;">💬</span>
    <h1>Chat en tiempo real</h1>
  </div>
  <div class="messages" id="messages">
    @forelse($messages as $m)
      <div class="msg" data-id="{{ $m->id }}">
        <span class="msg-user">{{ $m->user ?? 'Anon' }}</span>
        <div class="msg-content">{{ $m->content }}</div>
        <div class="msg-meta">{{ $m->created_at }}</div>
      </div>
    @empty
      <div style="text-align:center;color:#888;">No hay mensajes aún.</div>
    @endforelse
  </div>
  <form class="chat-form" onsubmit="return false;">
    <input id="user" type="text" placeholder="Tu nombre (opcional)" autocomplete="off" />
    <input id="content" type="text" placeholder="Escribe tu mensaje..." style="flex:1" autocomplete="off" />
    <button id="send" type="submit">Enviar</button>
  </form>
</div>
<script>
document.querySelector('.chat-form').addEventListener('submit', async (e) => {
    const user = document.getElementById('user').value;
    const content = document.getElementById('content').value.trim();
    if (!content) return;

    const res = await fetch("{{ route('chat.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user,
            content
        })
    });

    if (res.ok) document.getElementById('content').value = '';
});
</script>
</body>
</html>