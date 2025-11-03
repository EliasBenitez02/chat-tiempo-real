<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Chat en tiempo real | Laravel + Reverb</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/chat.css', 'resources/js/app.js'])
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'sans': ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
          },
          borderWidth: {
            '3': '3px',
          },
          keyframes: {
            fadeInUp: {
              'from': { 
                transform: 'translateY(10px)',
                opacity: '0'
              },
              'to': { 
                transform: 'translateY(0)',
                opacity: '1'
              }
            }
          },
          animation: {
            fadeInUp: 'fadeInUp 0.5s ease-out forwards'
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen flex items-center justify-center p-5 body-gradient font-[Inter]">
<div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
  <div class="chat-gradient text-white p-6 flex items-center gap-3 shadow-md">
    <span class="text-3xl animate-bounce">💬</span>
    <div>
      <h1 class="text-2xl font-bold tracking-tight m-0">Chat en tiempo real</h1>
      <div class="text-sm opacity-90 mt-0.5">Powered by Laravel + Reverb</div>
    </div>
  </div>
  
  <div id="messages" class="flex-1 overflow-y-auto p-6 bg-gradient-to-b from-gray-50 to-white messages">
    @forelse($messages as $m)
      <div class="mb-4 bg-white p-5 rounded-xl shadow-sm border-l-3 border-indigo-500 transition-all hover:-translate-y-0.5 hover:shadow-md animate-fadeInUp group" data-id="{{ $m->id }}">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2 text-indigo-500 font-semibold text-[0.95rem]">
            <span class="text-xl group-hover:scale-110 transition-transform">👤</span>
            <span class="bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">
              {{ $m->user ?? 'Anónimo' }}
            </span>
          </div>
          <div class="flex items-center gap-2 text-xs font-medium bg-indigo-50 px-3 py-1.5 rounded-full">
            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-indigo-600">
              {{ \Carbon\Carbon::parse($m->created_at)->timezone('America/Argentina/Buenos_Aires')->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
            </span>
          </div>
        </div>
        <div class="text-slate-800 leading-relaxed text-[0.95rem] pl-3 border-l-2 border-indigo-100 group-hover:border-indigo-300 transition-colors">{{ $m->content }}</div>
      </div>
    @empty
      <div class="text-center py-10 px-5 text-slate-400">
        <div class="text-5xl mb-3 opacity-50">💭</div>
        <div>No hay mensajes aún. ¡Sé el primero en escribir!</div>
      </div>
    @endforelse
  </div>
  
  <div class="p-5 pb-6 bg-white border-t border-gray-200">
    <form class="space-y-3" onsubmit="return false;">
      <div class="flex gap-2.5 sm:flex-row flex-col">
        <input 
          id="user" 
          type="text" 
          placeholder="Tu nombre" 
          autocomplete="off"
          class="w-full sm:w-36 px-4 py-3 rounded-xl border-2 border-gray-200 text-[0.95rem] bg-gray-50 focus:border-indigo-500 focus:bg-white focus:ring-3 focus:ring-indigo-100 transition-all outline-none"
        />
        <input 
          id="content" 
          type="text" 
          placeholder="Escribe tu mensaje..." 
          autocomplete="off"
          class="flex-1 px-4 py-3 rounded-xl border-2 border-gray-200 text-[0.95rem] bg-gray-50 focus:border-indigo-500 focus:bg-white focus:ring-3 focus:ring-indigo-100 transition-all outline-none"
        />
      </div>
      <button 
        id="send" 
        type="submit"
        class="chat-gradient text-white px-7 py-3 rounded-xl text-[0.95rem] font-semibold cursor-pointer transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-indigo-200 active:translate-y-0 flex items-center gap-1.5 shadow-md shadow-indigo-200"
      >
        <span class="text-lg">📤</span>
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
const userInput = document.getElementById('user');

// Capitalizar cuando se escribe
userInput.addEventListener('input', (e) => {
  const cursorPos = e.target.selectionStart;
  const value = e.target.value;
  const capitalized = capitalizeName(value);
  
  if (value !== capitalized) {
    e.target.value = capitalized;
    e.target.setSelectionRange(cursorPos, cursorPos);
  }
});

// Capitalizar al inicio y al perder foco
userInput.addEventListener('blur', (e) => {
  e.target.value = capitalizeName(e.target.value);
});

// Función para añadir un mensaje al contenedor
function formatDateTime() {
  // Crear fecha en zona horaria de Argentina
  const now = new Date();
  const formatter = new Intl.DateTimeFormat('es-AR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
    timeZone: 'America/Argentina/Buenos_Aires'
  });
  
  // Formatear la fecha
  let parts = formatter.formatToParts(now);
  let formatted = '';
  
  // Construir el string manualmente para asegurar el formato correcto
  parts.forEach(part => {
    switch(part.type) {
      case 'day':
        formatted += part.value + ' ';
        break;
      case 'month':
        formatted += part.value + ' ';
        break;
      case 'year':
        formatted += part.value + ', ';
        break;
      case 'hour':
        formatted += part.value + ':';
        break;
      case 'minute':
        formatted += part.value;
        break;
    }
  });
  
  return formatted;
}

function appendMessage(message, isNew = false) {
  const messagesContainer = document.getElementById('messages');
  const emptyState = messagesContainer.querySelector('.empty-state');
  if (emptyState) emptyState.remove();

  // Asegurar que el nombre y el mensaje tengan la primera letra en mayúscula
  message.user = message.user ? capitalizeName(message.user) : 'Anónimo';
  message.content = message.content.charAt(0).toUpperCase() + message.content.slice(1);

  const dateStr = message.created_at || formatDateTime();
  const msgHtml = `
    <div class="mb-4 bg-white p-5 rounded-xl shadow-sm border-l-3 border-indigo-500 transition-all hover:-translate-y-0.5 hover:shadow-md group">
      <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2 text-indigo-500 font-semibold text-[0.95rem]">
          <span class="text-xl group-hover:scale-110 transition-transform">👤</span>
          <span class="bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">
            ${message.user}
          </span>
        </div>
        <div class="flex items-center gap-2 text-xs font-medium bg-indigo-50 px-3 py-1.5 rounded-full">
          <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="text-indigo-600">${dateStr}</span>
        </div>
      </div>
      <div class="text-slate-800 leading-relaxed text-[0.95rem] pl-3 border-l-2 border-indigo-100 group-hover:border-indigo-300 transition-colors">
        ${message.content}
      </div>
    </div>
  `.trim();

  // Crear un contenedor temporal
  const temp = document.createElement('div');
  temp.innerHTML = msgHtml;
  const msgDiv = temp.firstElementChild;
  
  if (message.id) msgDiv.dataset.id = message.id;

  // Añadir el mensaje con una animación suave
  requestAnimationFrame(() => {
    msgDiv.style.opacity = '0';
    msgDiv.style.transform = 'translateY(10px)';
    messagesContainer.appendChild(msgDiv);

    // Scroll inmediato para mensajes nuevos
    if (isNew) {
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Animar la entrada del mensaje
    requestAnimationFrame(() => {
      msgDiv.style.transition = 'all 0.2s ease-out';
      msgDiv.style.opacity = '1';
      msgDiv.style.transform = 'translateY(0)';
    });
  });
}

// Manejar envío del formulario
document.querySelector('form').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const sendBtn = document.getElementById('send');
  if (sendBtn.disabled) return; // Evitar envíos múltiples
  
  const userInput = document.getElementById('user');
  const contentInput = document.getElementById('content');
  
  let content = contentInput.value.trim();
  if (!content) return;
  
  // Asegurar que el contenido del mensaje comience con mayúscula
  content = content.charAt(0).toUpperCase() + content.slice(1);
  const user = capitalizeName(userInput.value || 'Anónimo');

  // Deshabilitar botón mientras se envía
  sendBtn.disabled = true;
  sendBtn.style.opacity = '0.6';
  
  // Preparar el mensaje local inmediatamente
  const localMessage = { 
    user, 
    content,
    created_at: formatDateTime()
  };

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

    // Mostrar el mensaje localmente primero para feedback inmediato
    appendMessage(localMessage, true);
    
    // Limpiar input y dar focus
    contentInput.value = '';
    contentInput.focus();

    const responseData = await res.text();
    
    if (!res.ok) {
      throw new Error(`Error ${res.status}: ${responseData}`);
    }

    // Actualizar el mensaje con datos del servidor si es necesario
    try {
      const json = JSON.parse(responseData);
      if (json.id) {
        // El mensaje ya está mostrado, solo actualizamos si hay cambios necesarios
        const msgElement = document.querySelector(`[data-id="${json.id}"]`);
        if (msgElement) {
          // Actualizar solo si hay cambios significativos
          if (json.created_at) {
            const timeElement = msgElement.querySelector('.text-indigo-600');
            if (timeElement) {
              timeElement.textContent = json.created_at;
            }
          }
        }
      }
    } catch (err) {
      console.warn('La respuesta no es JSON:', err);
    }
  } catch (error) {
    console.error('Error al enviar mensaje:', error);
    alert('Error al enviar el mensaje. Por favor, intenta de nuevo.');
  } finally {
    // Reactivar el botón
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