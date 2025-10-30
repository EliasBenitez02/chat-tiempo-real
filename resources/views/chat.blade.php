<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Chat en tiempo real | Laravel + Reverb</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    
    .chat-container {
      max-width: 520px;
      width: 100%;
      background: #ffffff;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      max-height: 90vh;
    }
    
    .chat-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff;
      padding: 24px 28px;
      display: flex;
      align-items: center;
      gap: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .chat-header-icon {
      font-size: 2rem;
      animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }
    
    .chat-header h1 {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
      letter-spacing: -0.02em;
    }
    
    .chat-header-subtitle {
      font-size: 0.85rem;
      opacity: 0.9;
      margin-top: 2px;
      font-weight: 400;
    }
    
    .messages {
      flex: 1;
      overflow-y: auto;
      padding: 24px;
      background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    }
    
    .messages::-webkit-scrollbar {
      width: 6px;
    }
    
    .messages::-webkit-scrollbar-track {
      background: transparent;
    }
    
    .messages::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }
    
    .messages::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
    
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #94a3b8;
    }
    
    .empty-state-icon {
      font-size: 3rem;
      margin-bottom: 12px;
      opacity: 0.5;
    }
    
    .msg {
      margin-bottom: 16px;
      animation: slideIn 0.3s ease-out;
      background: #ffffff;
      padding: 14px 16px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
      border-left: 3px solid #667eea;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .msg:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .msg-user {
      font-weight: 600;
      color: #667eea;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .msg-user::before {
      content: '👤';
      font-size: 0.85rem;
    }
    
    .msg-content {
      margin: 8px 0 6px 0;
      color: #1e293b;
      line-height: 1.5;
      font-size: 0.95rem;
    }
    
    .msg-meta {
      font-size: 0.75rem;
      color: #94a3b8;
      font-weight: 500;
    }
    
    .chat-form-wrapper {
      padding: 20px 24px 24px;
      background: #ffffff;
      border-top: 1px solid #e2e8f0;
    }
    
    .input-group {
      display: flex;
      gap: 10px;
      margin-bottom: 12px;
    }
    
    .chat-form input {
      padding: 12px 16px;
      border-radius: 12px;
      border: 2px solid #e2e8f0;
      font-size: 0.95rem;
      outline: none;
      transition: all 0.2s;
      font-family: 'Inter', sans-serif;
      background: #f8fafc;
    }
    
    .chat-form input:focus {
      border-color: #667eea;
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    #user {
      width: 140px;
    }
    
    #content {
      flex: 1;
    }
    
    .chat-form button {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: 12px 28px;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      font-family: 'Inter', sans-serif;
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .chat-form button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }
    
    .chat-form button:active {
      transform: translateY(0);
    }
    
    .send-icon {
      font-size: 1.1rem;
    }
    
    @media (max-width: 500px) {
      .input-group {
        flex-direction: column;
      }
      
      #user {
        width: 100%;
      }
    }
  </style>
</head>
<body>
<div class="chat-container">
  <div class="chat-header">
    <span class="chat-header-icon">💬</span>
    <div>
      <h1>Chat en tiempo real</h1>
      <div class="chat-header-subtitle">Powered by Laravel + Reverb</div>
    </div>
  </div>
  
  <div class="messages" id="messages">
    @forelse($messages as $m)
      <div class="msg" data-id="{{ $m->id }}">
        <div class="msg-user">{{ $m->user ?? 'Anónimo' }}</div>
        <div class="msg-content">{{ $m->content }}</div>
        <div class="msg-meta">{{ $m->created_at }}</div>
      </div>
    @empty
      <div class="empty-state">
        <div class="empty-state-icon">💭</div>
        <div>No hay mensajes aún. ¡Sé el primero en escribir!</div>
      </div>
    @endforelse
  </div>
  
  <div class="chat-form-wrapper">
    <form class="chat-form" onsubmit="return false;">
      <div class="input-group">
        <input 
          id="user" 
          type="text" 
          placeholder="Tu nombre" 
          autocomplete="off"
        />
        <input 
          id="content" 
          type="text" 
          placeholder="Escribe tu mensaje..." 
          autocomplete="off"
        />
      </div>
      <button id="send" type="submit">
        <span class="send-icon">📤</span>
        Enviar
      </button>
    </form>
  </div>
</div>
<script>
// Función para capitalizar la primera letra de cada palabra
function capitalizeName(name) {
  return name
    .trim()
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
    .join(' ');
}

// Aplicar capitalización automática al campo de nombre
document.getElementById('user').addEventListener('input', (e) => {
  const cursorPos = e.target.selectionStart;
  const value = e.target.value;
  const capitalized = capitalizeName(value);
  
  if (value !== capitalized) {
    e.target.value = capitalized;
    e.target.setSelectionRange(cursorPos, cursorPos);
  }
});

// Función para añadir un mensaje al contenedor
function appendMessage(message, isNew = false) {
  const messagesContainer = document.getElementById('messages');
  const emptyState = messagesContainer.querySelector('.empty-state');
  if (emptyState) emptyState.remove();

  const msgDiv = document.createElement('div');
  msgDiv.className = 'msg';
  if (message.id) msgDiv.dataset.id = message.id;

  const nameDiv = document.createElement('div');
  nameDiv.className = 'msg-user';
  nameDiv.textContent = message.user || 'Anónimo';

  const contentDiv = document.createElement('div');
  contentDiv.className = 'msg-content';
  contentDiv.textContent = message.content;

  const metaDiv = document.createElement('div');
  metaDiv.className = 'msg-meta';
  metaDiv.textContent = message.created_at || 'Ahora';

  msgDiv.appendChild(nameDiv);
  msgDiv.appendChild(contentDiv);
  msgDiv.appendChild(metaDiv);

  if (isNew) msgDiv.style.animation = 'slideIn 0.3s ease-out';

  messagesContainer.appendChild(msgDiv);
  messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Manejar envío del formulario
document.querySelector('.chat-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const sendBtn = document.getElementById('send');
  const userInput = document.getElementById('user');
  const contentInput = document.getElementById('content');
  
  const content = contentInput.value.trim();
  if (!content) return;

  const user = capitalizeName(userInput.value || 'Anónimo');

  // Deshabilitar botón mientras se envía
  sendBtn.disabled = true;
  sendBtn.style.opacity = '0.6';

  try {
    const res = await fetch("{{ route('chat.store') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ user, content })
    });

    const responseData = await res.text();
    console.log('Respuesta del servidor:', responseData);

    if (res.ok) {
      // Limpiar input
      contentInput.value = '';
      contentInput.focus();

      // Añadir mensaje a la UI
      let message = { user, content };
      try {
        const json = JSON.parse(responseData);
        message = { ...message, ...json };
      } catch (err) {
        console.warn('La respuesta no es JSON:', err);
      }
      
      appendMessage(message, true);
    } else {
      throw new Error(`Error ${res.status}: ${responseData}`);
    }
  } catch (error) {
    console.error('Error al enviar mensaje:', error);
    alert('Error al enviar el mensaje. Por favor, intenta de nuevo.');
  } finally {
    sendBtn.disabled = false;
    sendBtn.style.opacity = '1';
  }
});

// Auto-scroll al final cuando llegan nuevos mensajes
const messagesContainer = document.querySelector('.messages');
const observer = new MutationObserver(() => {
  messagesContainer.scrollTop = messagesContainer.scrollHeight;
});
observer.observe(messagesContainer, { childList: true, subtree: true });
</script>
</body>
</html>