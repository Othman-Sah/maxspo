
import React, { useState } from 'react';
import { ArrowLeft, Dumbbell, Zap, CreditCard, Palette, Target, Flower2, Flame, Info, CheckCircle2 } from 'lucide-react';

interface AddActivityViewProps {
  onBack: () => void;
}

const ICONS = [
  { id: 'Dumbbell', icon: Dumbbell, label: 'Muscu' },
  { id: 'Target', icon: Target, label: 'Cible' },
  { id: 'Flower2', icon: Flower2, label: 'Zen' },
  { id: 'Flame', icon: Flame, label: 'Intense' },
];

const COLORS = [
  { id: 'indigo', class: 'from-indigo-500 to-blue-600', hex: '#6366f1' },
  { id: 'rose', class: 'from-rose-500 to-red-600', hex: '#f43f5e' },
  { id: 'emerald', class: 'from-emerald-500 to-teal-600', hex: '#10b981' },
  { id: 'amber', class: 'from-amber-500 to-orange-600', hex: '#f59e0b' },
];

const AddActivityView: React.FC<AddActivityViewProps> = ({ onBack }) => {
  const [name, setName] = useState('');
  const [selectedIcon, setSelectedIcon] = useState('Dumbbell');
  const [selectedColor, setSelectedColor] = useState('indigo');
  const [price, setPrice] = useState('250');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    alert('Activité créée avec succès !');
    onBack();
  };

  return (
    <div className="animate-in fade-in slide-in-from-bottom-8 duration-500 max-w-4xl mx-auto space-y-8">
      <div className="flex items-center justify-between">
        <button onClick={onBack} className="flex items-center gap-2 text-slate-500 hover:text-indigo-600 font-bold transition-colors">
          <ArrowLeft size={20} /> Retour
        </button>
        <h1 className="text-2xl font-black text-slate-900">Nouvelle Activité Sportive</h1>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2 space-y-6">
          <form onSubmit={handleSubmit} className="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm space-y-6">
            <div className="space-y-2">
              <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nom du Sport</label>
              <input 
                type="text" 
                required
                placeholder="Ex: Muay Thaï"
                className="w-full px-5 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-lg"
                value={name}
                onChange={e => setName(e.target.value)}
              />
            </div>

            <div className="space-y-4">
              <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest">Icône Représentative</label>
              <div className="grid grid-cols-4 gap-4">
                {ICONS.map(item => (
                  <button
                    key={item.id}
                    type="button"
                    onClick={() => setSelectedIcon(item.id)}
                    className={`flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all ${
                      selectedIcon === item.id ? 'border-indigo-500 bg-indigo-50 text-indigo-600' : 'border-slate-50 text-slate-400 hover:border-slate-200'
                    }`}
                  >
                    <item.icon size={24} />
                    <span className="text-[10px] font-black mt-2 uppercase">{item.label}</span>
                  </button>
                ))}
              </div>
            </div>

            <div className="space-y-4">
              <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest">Thème Visuel</label>
              <div className="flex gap-4">
                {COLORS.map(color => (
                  <button
                    key={color.id}
                    type="button"
                    onClick={() => setSelectedColor(color.id)}
                    className={`h-12 w-12 rounded-full border-4 transition-all ${
                      selectedColor === color.id ? 'border-slate-900 scale-110' : 'border-white shadow-sm'
                    } bg-gradient-to-r ${color.class}`}
                  />
                ))}
              </div>
            </div>

            <div className="grid grid-cols-2 gap-6 pt-4">
              <div className="space-y-2">
                <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest">Prix Mensuel (DH)</label>
                <div className="relative">
                  <CreditCard size={18} className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                  <input 
                    type="number" 
                    className="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-black text-xl text-emerald-600"
                    value={price}
                    onChange={e => setPrice(e.target.value)}
                  />
                </div>
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest">Accès Sauna</label>
                <div className="h-14 bg-indigo-50 rounded-2xl flex items-center justify-between px-6 border border-indigo-100">
                  <span className="text-xs font-bold text-indigo-700">Inclus par défaut</span>
                  <CheckCircle2 size={20} className="text-indigo-600" />
                </div>
              </div>
            </div>

            <button type="submit" className="w-full py-4 bg-slate-900 text-white font-black rounded-2xl shadow-xl shadow-slate-200 hover:bg-slate-800 transition-all active:scale-[0.98]">
              Créer l'activité
            </button>
          </form>
        </div>

        <div className="space-y-6">
          <div className="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm">
            <h3 className="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Aperçu de la carte</h3>
            <div className={`bg-gradient-to-br ${COLORS.find(c => c.id === selectedColor)?.class} p-6 rounded-3xl text-white shadow-lg`}>
              <div className="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                {selectedIcon === 'Dumbbell' && <Dumbbell size={24} />}
                {selectedIcon === 'Target' && <Target size={24} />}
                {selectedIcon === 'Flower2' && <Flower2 size={24} />}
                {selectedIcon === 'Flame' && <Flame size={24} />}
              </div>
              <h4 className="text-xl font-black">{name || 'Nom du Sport'}</h4>
              <p className="text-sm font-medium opacity-80 mt-1">{price} DH / Mois</p>
            </div>
            
            <div className="mt-8 flex items-start gap-4 p-4 bg-amber-50 rounded-2xl border border-amber-100">
              <Info className="text-amber-500 shrink-0" size={20} />
              <p className="text-[11px] font-bold text-amber-700 leading-relaxed">
                Les activités créées sont immédiatement disponibles dans le menu de sélection lors de l'ajout d'un membre.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AddActivityView;
