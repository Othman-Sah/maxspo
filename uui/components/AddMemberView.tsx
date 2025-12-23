
import React, { useState, useEffect } from 'react';
import { 
  UserPlus, 
  ArrowLeft, 
  User, 
  Mail, 
  Phone, 
  Calendar, 
  Dumbbell, 
  CreditCard, 
  Waves, 
  ShieldCheck,
  Zap
} from 'lucide-react';
import { MOCK_ACTIVITIES } from '../constants';

interface AddMemberViewProps {
  onBack: () => void;
}

const AddMemberView: React.FC<AddMemberViewProps> = ({ onBack }) => {
  const [formData, setFormData] = useState({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    age: '',
    sportId: '',
    duration: 1,
    joinDate: new Date().toISOString().split('T')[0],
    paymentMethod: 'especes'
  });

  const [totalPrice, setTotalPrice] = useState(0);

  useEffect(() => {
    const selectedSport = MOCK_ACTIVITIES.find(a => a.id === formData.sportId);
    if (selectedSport) {
      setTotalPrice(selectedSport.monthlyPrice * formData.duration);
    } else {
      setTotalPrice(0);
    }
  }, [formData.sportId, formData.duration]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    alert('Membre ajouté avec succès ! (Simulation)');
    onBack();
  };

  return (
    <div className="animate-in fade-in slide-in-from-bottom-8 duration-500 max-w-5xl mx-auto space-y-8">
      {/* Top Header */}
      <div className="flex items-center justify-between">
        <button 
          onClick={onBack}
          className="flex items-center gap-2 text-slate-500 hover:text-indigo-600 font-bold transition-colors group"
        >
          <div className="p-2 bg-white border border-slate-200 rounded-xl group-hover:border-indigo-100 group-hover:bg-indigo-50 transition-all">
            <ArrowLeft size={20} />
          </div>
          Retour à la liste
        </button>
        <div className="text-right hidden md:block">
          <span className="text-xs font-black uppercase text-slate-400 tracking-widest">Enregistrement</span>
          <p className="text-sm font-bold text-slate-900">Nouveau Membre NEEDSPORT Pro</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Main Form Column */}
        <div className="lg:col-span-2 space-y-8">
          <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h2 className="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
              <div className="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                <User size={24} />
              </div>
              Informations Personnelles
            </h2>
            
            <form id="add-member-form" onSubmit={handleSubmit} className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                  <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Prénom</label>
                  <input 
                    type="text" 
                    required
                    placeholder="Ex: Yassine"
                    className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                    value={formData.firstName}
                    onChange={e => setFormData({...formData, firstName: e.target.value})}
                  />
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Nom de famille</label>
                  <input 
                    type="text" 
                    required
                    placeholder="Ex: Benali"
                    className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                    value={formData.lastName}
                    onChange={e => setFormData({...formData, lastName: e.target.value})}
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                  <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 flex items-center gap-1.5">
                    <Mail size={12}/> Email
                  </label>
                  <input 
                    type="email" 
                    required
                    placeholder="nom@exemple.com"
                    className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                    value={formData.email}
                    onChange={e => setFormData({...formData, email: e.target.value})}
                  />
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 flex items-center gap-1.5">
                    <Phone size={12}/> Téléphone
                  </label>
                  <input 
                    type="tel" 
                    required
                    placeholder="06 -- -- -- --"
                    className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                    value={formData.phone}
                    onChange={e => setFormData({...formData, phone: e.target.value})}
                  />
                </div>
              </div>

              <div className="w-1/2 space-y-2">
                <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Âge</label>
                <input 
                  type="number" 
                  required
                  min="12"
                  max="100"
                  placeholder="25"
                  className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                  value={formData.age}
                  onChange={e => setFormData({...formData, age: e.target.value})}
                />
              </div>
            </form>
          </div>

          <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h2 className="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
              <div className="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <Dumbbell size={24} />
              </div>
              Détails de l'Abonnement
            </h2>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div className="space-y-2">
                <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Sport / Activité</label>
                <select 
                  className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                  value={formData.sportId}
                  onChange={e => setFormData({...formData, sportId: e.target.value})}
                  required
                >
                  <option value="">Sélectionner une activité</option>
                  {MOCK_ACTIVITIES.map(activity => (
                    <option key={activity.id} value={activity.id}>
                      {activity.name} — {activity.monthlyPrice} DH/mois
                    </option>
                  ))}
                </select>
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Durée (Mois)</label>
                <input 
                  type="number" 
                  min="1" 
                  max="24"
                  className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                  value={formData.duration}
                  onChange={e => setFormData({...formData, duration: parseInt(e.target.value) || 1})}
                />
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="space-y-2">
                <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 flex items-center gap-1.5">
                  <Calendar size={12}/> Date de début
                </label>
                <input 
                  type="date" 
                  className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                  value={formData.joinDate}
                  onChange={e => setFormData({...formData, joinDate: e.target.value})}
                />
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 flex items-center gap-1.5">
                  <CreditCard size={12}/> Mode de Paiement
                </label>
                <select 
                  className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                  value={formData.paymentMethod}
                  onChange={e => setFormData({...formData, paymentMethod: e.target.value})}
                >
                  <option value="especes">💵 Espèces</option>
                  <option value="carte">💳 Carte Bancaire</option>
                  <option value="virement">🏦 Virement</option>
                  <option value="cheque">📝 Chèque</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        {/* Summary Sticky Column */}
        <div className="space-y-6">
          <div className="bg-slate-900 rounded-3xl p-8 text-white shadow-2xl shadow-slate-200 sticky top-28 overflow-hidden">
            {/* Background Accent */}
            <div className="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
            
            <div className="relative z-10 space-y-8">
              <div>
                <h3 className="text-xl font-black mb-1">Résumé de l'inscription</h3>
                <p className="text-slate-400 text-xs font-bold uppercase tracking-widest">NEEDSPORT Pro 2024</p>
              </div>

              <div className="space-y-4 pt-4 border-t border-slate-800">
                <div className="flex justify-between items-center">
                  <span className="text-slate-400 text-sm font-bold">Activité</span>
                  <span className="font-black">
                    {MOCK_ACTIVITIES.find(a => a.id === formData.sportId)?.name || 'Non sélectionné'}
                  </span>
                </div>
                <div className="flex justify-between items-center">
                  <span className="text-slate-400 text-sm font-bold">Durée</span>
                  <span className="font-black">{formData.duration} Mois</span>
                </div>
                <div className="flex justify-between items-center p-3 bg-white/5 rounded-2xl border border-white/10">
                  <span className="text-indigo-300 text-sm font-black flex items-center gap-2">
                    <Waves size={16}/> Sauna
                  </span>
                  <span className="text-[10px] font-black uppercase bg-emerald-500 text-white px-2 py-0.5 rounded-full">Gratuit</span>
                </div>
              </div>

              <div className="pt-8 border-t border-slate-800">
                <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total à régler</p>
                <div className="flex items-baseline gap-2">
                  <h4 className="text-4xl font-black text-white">{totalPrice.toLocaleString('fr-FR')}</h4>
                  <span className="text-slate-400 font-bold text-lg">DH</span>
                </div>
              </div>

              <div className="space-y-4">
                <button 
                  form="add-member-form"
                  type="submit"
                  disabled={!formData.sportId}
                  className={`w-full py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-lg flex items-center justify-center gap-2 ${
                    formData.sportId 
                    ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-500/20 active:scale-95' 
                    : 'bg-slate-800 text-slate-500 cursor-not-allowed'
                  }`}
                >
                  <ShieldCheck size={18} />
                  Valider l'Inscription
                </button>
                <div className="flex items-center gap-2 justify-center text-slate-500 text-[10px] font-black uppercase tracking-widest">
                  <Zap size={10} />
                  Activation instantanée
                </div>
              </div>
            </div>
          </div>

          <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
             <div className="flex items-start gap-4">
               <div className="p-3 bg-amber-50 text-amber-600 rounded-2xl">
                 <ShieldCheck size={24} />
               </div>
               <div>
                 <h4 className="text-sm font-black text-slate-900">Engagement Qualité</h4>
                 <p className="text-xs text-slate-500 font-medium mt-1 leading-relaxed">
                   En validant ce formulaire, vous confirmez que le membre a accepté les conditions générales du club.
                 </p>
               </div>
             </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AddMemberView;
