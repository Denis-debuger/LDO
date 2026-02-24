import React, { useEffect, useMemo, useState } from 'react';
import { createRoot } from 'react-dom/client';

function bmiInfo(heightCm, weightKg) {
  if (!heightCm || !weightKg) return { value: null, label: 'Введите рост и вес' };
  const h = Number(heightCm) / 100;
  const bmi = Number(weightKg) / (h * h);
  if (!Number.isFinite(bmi) || bmi <= 0) return { value: null, label: 'Введите корректные данные' };
  let label = 'Ожирение';
  if (bmi < 18.5) label = 'Дефицит массы';
  else if (bmi < 25) label = 'Норма';
  else if (bmi < 30) label = 'Избыточная масса';
  return { value: bmi.toFixed(1), label };
}

function App() {
  const [profile, setProfile] = useState({
    height_cm: '',
    weight_kg: '',
    age: '',
    gender: '',
    activity_level: 'moderate',
    goal: 'maintain',
  });
  const [status, setStatus] = useState('');

  useEffect(() => {
    fetch('/backend/public/api.php?endpoint=profile', { credentials: 'include' })
      .then((r) => (r.ok ? r.json() : Promise.reject(r.status)))
      .then((data) => {
        if (data?.profile) setProfile((prev) => ({ ...prev, ...data.profile }));
      })
      .catch(() => setStatus('Не удалось загрузить профиль через API (возможно, вы не авторизованы).'));
  }, []);

  const bmi = useMemo(() => bmiInfo(profile.height_cm, profile.weight_kg), [profile.height_cm, profile.weight_kg]);

  async function onSave(e) {
    e.preventDefault();
    setStatus('Сохраняем...');
    const resp = await fetch('/backend/public/api.php?endpoint=profile', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include',
      body: JSON.stringify(profile),
    });
    const data = await resp.json().catch(() => ({}));
    if (resp.ok && data.ok) setStatus('Профиль сохранён');
    else setStatus(data.error || 'Ошибка сохранения');
  }

  return (
    <main style={{ maxWidth: 860, margin: '40px auto', fontFamily: 'system-ui, sans-serif', padding: 16 }}>
      <h1>Frontend (React SPA)</h1>
      <p>Отдельный фронтенд для работы с backend API.</p>
      <form onSubmit={onSave} style={{ display: 'grid', gap: 12 }}>
        <input placeholder="Рост (см)" value={profile.height_cm ?? ''} onChange={(e) => setProfile({ ...profile, height_cm: e.target.value })} />
        <input placeholder="Вес (кг)" value={profile.weight_kg ?? ''} onChange={(e) => setProfile({ ...profile, weight_kg: e.target.value })} />
        <input placeholder="Возраст" value={profile.age ?? ''} onChange={(e) => setProfile({ ...profile, age: e.target.value })} />
        <button type="submit">Сохранить</button>
      </form>
      <div style={{ marginTop: 16, padding: 12, border: '1px solid #ddd', borderRadius: 8 }}>
        <strong>ИМТ:</strong> {bmi.value ?? '—'} · {bmi.label}
      </div>
      {status && <p style={{ marginTop: 12 }}>{status}</p>}
    </main>
  );
}

createRoot(document.getElementById('root')).render(<App />);
