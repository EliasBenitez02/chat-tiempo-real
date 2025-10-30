// resources/js/chat.js
// Asume que Echo ya quedó en window por bootstrap.js
if (window.Echo) {
    window.Echo.channel("chat").listen(".message.posted", (e) => {
        const box = document.getElementById("messages");
        const div = document.createElement("div");
        div.className = "msg";
        div.dataset.id = e.id;
        div.innerHTML = `<div><strong>${e.user ?? "Anon"}:</strong> ${
            e.content
        }</div>
                       <div class="meta">${e.created_at}</div>`;
        box.appendChild(div);
        box.scrollTop = box.scrollHeight;
    });
} else {
    console.warn("Echo aún no está listo");
}
