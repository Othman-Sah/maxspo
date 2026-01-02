
import React, { useState, useRef, useEffect } from 'react';
import { 
  Users, 
  CreditCard, 
  Timer, 
  Award, 
  Search, 
  Bell,
  BarChart3,
  TrendingUp,
  Waves,
  ChevronDown,
  Calendar,
  CreditCard as CardIcon,
  Printer,
  Download,
  Mail,
  Phone,
  User,
  ShieldAlert,
  Clock,
  Dumbbell as ActivityIcon,
  Plus,
  Trash2,
  FileText,
  CheckCircle2,
  Package,
  QrCode,
  Trophy
} from 'lucide-react';
import { 
  BarChart, 
  Bar, 
  XAxis, 
  YAxis, 
  CartesianGrid, 
  Tooltip as RechartsTooltip, 
  ResponsiveContainer, 
  Cell,
  AreaChart,
  Area
} from 'recharts';
import Sidebar from './components/Sidebar';
import StatCard from './components/StatCard';
import MemberRow from './components/MemberRow';
import MembersView from './components/MembersView';
import ActivitiesView from './components/ActivitiesView';
import PaymentsView from './components/PaymentsView';
import AddMemberView from './components/AddMemberView';
import AddActivityView from './components/AddActivityView';
import SettingsView from './components/SettingsView';
import NotificationsView from './components/NotificationsView';
import NotificationDropdown from './components/NotificationDropdown';
import ProfileDropdown from './components/ProfileDropdown';
import QuickActionModal from './components/QuickActionModal';
import ScheduleView from './components/ScheduleView';
import FinancialsView from './components/FinancialsView';
import StaffView from './components/StaffView';
import POSView from './components/POSView';
import LoginView from './components/LoginView';
import { MOCK_STATS, MOCK_SPORTS, MOCK_REVENUE, MOCK_NOTIFICATIONS, MOCK_ACTIVITIES } from './constants';
import { Member, StaffMember, Expense } from './types';

const App: React.FC = () => {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [activeTab, setActiveTab] = useState('dashboard');
  const [searchQuery, setSearchQuery] = useState('');
  const [isNotifOpen, setIsNotifOpen] = useState(false);
  const [isProfileOpen, setIsProfileOpen] = useState(false);
  const [expiringMembers, setExpiringMembers] = useState<Member[]>([]);
  
  // Modal States
  const [modalConfig, setModalConfig] = useState<{
    isOpen: boolean;
    title: string;
    type: 'info' | 'warning' | 'success';
    content: React.ReactNode;
    confirmLabel?: string;
    onConfirm?: () => void;
  }>({
    isOpen: false,
    title: '',
    type: 'info',
    content: null
  });

  const openModal = (config: Omit<typeof modalConfig, 'isOpen'>) => {
    setModalConfig({ ...config, isOpen: true });
  };

  const closeModal = () => {
    setModalConfig(prev => ({ ...prev, isOpen: false }));
  };
  
  const unreadNotifs = MOCK_NOTIFICATIONS.filter(n => !n.isRead).length;
  
  const notifRef = useRef<HTMLDivElement>(null);
  const profileRef = useRef<HTMLDivElement>(null);

  // Fetch expiring members on mount
  useEffect(() => {
    const fetchExpiringMembers = async () => {
      try {
        const response = await fetch('http://localhost/lA/Backend/api/members.php');
        if (response.ok) {
          const data = await response.json();
          const members = Array.isArray(data) ? data : [];
          const expiring = members.filter((m: Member) => m.status === 'expirant' || m.status === 'expire');
          setExpiringMembers(expiring);
        }
      } catch (err) {
        console.error('Error fetching expiring members:', err);
        setExpiringMembers([]);
      }
    };
    
    fetchExpiringMembers();
  }, []);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (notifRef.current && !notifRef.current.contains(event.target as Node)) {
        setIsNotifOpen(false);
      }
      if (profileRef.current && !profileRef.current.contains(event.target as Node)) {
        setIsProfileOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  // Shared Handlers
  const handleRenew = (member: Member | string) => {
    const name = typeof member === 'string' ? member : `${member.firstName} ${member.lastName}`;
    openModal({
      title: `Renouvellement : ${name}`,
      type: 'info',
      confirmLabel: "Valider le paiement",
      onConfirm: () => alert(`Abonnement de ${name} renouvelé !`),
      content: (
        <div className="space-y-4">
          <div className="p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
            <p className="text-xs font-bold text-indigo-600 uppercase mb-2">Dernier abonnement expiré</p>
            <p className="text-sm font-black text-slate-700">Le 12 Juin 2024</p>
          </div>
          <div className="space-y-2">
            <label className="text-[10px] font-black uppercase text-slate-400">Durée de l'extension</label>
            <div className="grid grid-cols-3 gap-3">
              {['1 Mois', '3 Mois', '12 Mois'].map(d => (
                <button key={d} className="py-2 border-2 border-slate-100 rounded-xl hover:border-indigo-500 font-bold text-sm transition-all focus:border-indigo-500 focus:bg-indigo-50">{d}</button>
              ))}
            </div>
          </div>
          <div className="pt-4 border-t border-slate-100 flex justify-between items-center">
            <span className="text-sm font-bold text-slate-500">Total à payer</span>
            <span className="text-xl font-black text-emerald-600">250 DH</span>
          </div>
        </div>
      )
    });
  };

  const handlePrintReceipt = (member: Member) => {
    const activity = MOCK_ACTIVITIES.find(a => a.name === member.sport);
    openModal({
      title: "Reçu de Paiement",
      type: 'success',
      confirmLabel: "Imprimer PDF",
      onConfirm: () => window.print(),
      content: (
        <div className="p-6 bg-white border-2 border-dashed border-slate-200 rounded-3xl space-y-6">
          <div className="text-center space-y-2 border-b border-slate-100 pb-4">
            <div className="flex justify-center mb-2">
               <div className="bg-indigo-600 p-2 rounded-xl text-white shadow-lg">
                 <Trophy size={20} />
               </div>
            </div>
            <h4 className="text-lg font-black text-slate-900">NEEDSPORT PRO</h4>
            <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Casablanca, Maroc • 05 22 -- -- --</p>
          </div>
          
          <div className="space-y-4">
            <div className="flex justify-between items-start">
              <div className="space-y-1">
                <p className="text-[10px] font-black text-slate-400 uppercase">Membre</p>
                <p className="text-sm font-bold text-slate-900">{member.firstName} {member.lastName}</p>
                <p className="text-xs text-slate-500">ID: #{member.id}</p>
              </div>
              <div className="text-right space-y-1">
                <p className="text-[10px] font-black text-slate-400 uppercase">Date</p>
                <p className="text-sm font-bold text-slate-900">{new Date().toLocaleDateString('fr-FR')}</p>
              </div>
            </div>

            <div className="p-4 bg-slate-50 rounded-2xl space-y-3">
              <div className="flex justify-between items-center text-sm">
                <span className="font-medium text-slate-500">{member.sport} (Abonnement)</span>
                <span className="font-black text-slate-900">{activity?.monthlyPrice || '250'} DH</span>
              </div>
              <div className="flex justify-between items-center text-xs">
                <span className="font-medium text-slate-400 italic">Accès Sauna (Inclus)</span>
                <span className="font-bold text-emerald-500">GRATUIT</span>
              </div>
              <div className="pt-3 border-t border-slate-200 flex justify-between items-center">
                <span className="text-xs font-black uppercase text-slate-400">Total réglé</span>
                <span className="text-xl font-black text-indigo-600">{activity?.monthlyPrice || '250'} DH</span>
              </div>
            </div>
          </div>

          <div className="flex flex-col items-center justify-center pt-4 space-y-3">
            <QrCode size={64} className="text-slate-300" />
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Scannez pour accès salle</p>
          </div>
          
          <div className="text-center">
            <p className="text-[10px] text-slate-400 italic leading-tight font-medium">
              Merci de votre confiance. Gardez ce reçu comme preuve de paiement.<br/>
              Abonnement valable jusqu'au {new Date(member.expiryDate).toLocaleDateString('fr-FR')}
            </p>
          </div>
        </div>
      )
    });
  };

  const handleContact = (member: Member, type: 'mail' | 'phone') => {
    if (type === 'mail') {
      openModal({
        title: `Contacter ${member.firstName}`,
        type: 'info',
        confirmLabel: "Envoyer",
        onConfirm: () => alert("Email envoyé !"),
        content: (
          <div className="space-y-4">
            <p className="text-xs font-bold text-slate-400 uppercase">Destinataire: {member.email}</p>
            <textarea 
              rows={4}
              placeholder="Saisissez votre message..."
              className="w-full p-4 bg-slate-50 border border-slate-100 rounded-xl font-medium text-sm outline-none focus:border-indigo-300"
            />
          </div>
        )
      });
    } else {
      alert(`Appel simulé vers ${member.phone}...`);
    }
  };

  const handleModifySchedule = () => {
    openModal({
      title: "Gestion du Planning",
      type: 'info',
      confirmLabel: "Appliquer les changements",
      onConfirm: () => alert("Planning mis à jour avec succès !"),
      content: (
        <div className="space-y-6">
          <p className="text-sm text-slate-500 font-medium italic">Glissez-déposez les créneaux ou sélectionnez un créneau vide pour ajouter une activité.</p>
          <div className="space-y-3">
             <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                <div className="flex items-center gap-3">
                   <div className="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white"><ActivityIcon size={18} /></div>
                   <div>
                      <p className="text-sm font-bold">Fitness / Cardio</p>
                      <p className="text-[10px] font-black uppercase text-slate-400">Lundi • 08:00 - 12:00</p>
                   </div>
                </div>
                <button className="text-indigo-600 text-xs font-black hover:underline">Modifier</button>
             </div>
             <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                <div className="flex items-center gap-3">
                   <div className="h-10 w-10 bg-rose-500 rounded-xl flex items-center justify-center text-white"><ActivityIcon size={18} /></div>
                   <div>
                      <p className="text-sm font-bold">Boxe Anglaise</p>
                      <p className="text-[10px] font-black uppercase text-slate-400">Lundi • 18:00 - 21:00</p>
                   </div>
                </div>
                <button className="text-indigo-600 text-xs font-black hover:underline">Modifier</button>
             </div>
          </div>
          <button className="w-full py-3 bg-indigo-50 text-indigo-600 font-black rounded-xl border border-indigo-100 flex items-center justify-center gap-2 text-xs">
            <Plus size={14} /> Ajouter un nouveau créneau
          </button>
        </div>
      )
    });
  };

  // Finance Handlers
  const handleAddExpense = () => {
    openModal({
      title: "Déclarer une dépense",
      type: 'info',
      confirmLabel: "Enregistrer la dépense",
      onConfirm: () => alert("Dépense ajoutée !"),
      content: (
        <div className="space-y-4">
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Catégorie</label>
            <select className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm">
              <option>Loyer / Charges</option>
              <option>Électricité / Eau</option>
              <option>Maintenance Matériel</option>
              <option>Marketing / Pub</option>
              <option>Autres</option>
            </select>
          </div>
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Description</label>
            <input type="text" placeholder="Ex: Entretien Climatisation" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Montant (DH)</label>
            <input type="number" placeholder="0.00" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-black text-lg text-rose-500" />
          </div>
        </div>
      )
    });
  };

  const handleViewExpense = (expense: Expense) => {
    openModal({
      title: `Dépense : ${expense.category}`,
      type: 'info',
      confirmLabel: "Ok",
      content: (
        <div className="space-y-4">
           <div className="p-4 bg-slate-50 rounded-2xl">
              <p className="text-sm font-bold text-slate-700">{expense.description}</p>
              <p className="text-xs text-slate-400 mt-1">Date : {expense.date}</p>
           </div>
           <div className="flex justify-between items-center font-black">
              <span className="text-slate-500 uppercase text-[10px]">Montant Total</span>
              <span className="text-xl text-rose-500">{expense.amount} DH</span>
           </div>
        </div>
      )
    });
  };

  // Staff Handlers
  const handleAddStaff = () => {
    openModal({
      title: "Ajouter un employé",
      type: 'info',
      confirmLabel: "Engager",
      onConfirm: () => alert("Staff ajouté au club."),
      content: (
        <div className="space-y-4">
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Nom Complet</label>
            <input type="text" placeholder="Prénom Nom" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Rôle / Poste</label>
            <input type="text" placeholder="Ex: Coach Fitness" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Salaire Fixe (DH)</label>
            <input type="number" placeholder="0" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
        </div>
      )
    });
  };

  const handleEditStaff = (staff: StaffMember) => {
    openModal({
      title: `Modifier Staff : ${staff.name}`,
      type: 'info',
      confirmLabel: "Appliquer",
      onConfirm: () => alert(`Modifications appliquées pour ${staff.name}`),
      content: (
        <div className="space-y-4">
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Rôle</label>
            <input type="text" defaultValue={staff.role} className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Statut</label>
            <select defaultValue={staff.status} className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm">
              <option value="present">Présent</option>
              <option value="absent">Absent</option>
              <option value="en_pause">En Pause</option>
            </select>
          </div>
        </div>
      )
    });
  };

  const handleDeleteStaff = (staff: StaffMember) => {
    openModal({
      title: "Licencier / Supprimer staff",
      type: 'warning',
      confirmLabel: "Supprimer",
      onConfirm: () => alert(`${staff.name} retiré du staff.`),
      content: (
        <div className="space-y-3">
          <p className="text-sm text-slate-500 font-medium leading-relaxed">
            Êtes-vous sûr de vouloir retirer <span className="font-black text-slate-900">{staff.name}</span> de l'équipe ? 
            L'historique des paiements de salaire sera conservé.
          </p>
        </div>
      )
    });
  };

  // POS Handlers
  const handleCheckoutPOS = (subtotal: number, items: number) => {
    openModal({
      title: "Encaissement Snack/Complement",
      type: 'success',
      confirmLabel: "Imprimer Ticket",
      onConfirm: () => alert("Transaction finalisée."),
      content: (
        <div className="space-y-6">
           <div className="flex flex-col items-center justify-center p-8 bg-emerald-50 rounded-[32px] border border-emerald-100">
              <div className="h-16 w-16 bg-emerald-500 text-white rounded-full flex items-center justify-center mb-4 shadow-lg shadow-emerald-100">
                <CheckCircle2 size={32} />
              </div>
              <h4 className="text-2xl font-black text-emerald-600">{subtotal} DH</h4>
              <p className="text-[10px] font-black uppercase text-emerald-400 tracking-widest">{items} articles vendus</p>
           </div>
           <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
              <div className="flex justify-between text-xs font-bold text-slate-400 uppercase">
                <span>Méthode</span>
                <span className="text-slate-900">Espèces / Carte</span>
              </div>
              <div className="flex justify-between text-xs font-bold text-slate-400 uppercase">
                <span>Date</span>
                <span className="text-slate-900">{new Date().toLocaleDateString('fr-FR')}</span>
              </div>
           </div>
        </div>
      )
    });
  };

  const handleAddItemPOS = () => {
    openModal({
      title: "Ajouter un nouveau produit",
      type: 'info',
      confirmLabel: "Créer le produit",
      onConfirm: () => alert("Produit ajouté à l'inventaire POS."),
      content: (
        <div className="space-y-4">
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Nom du Produit</label>
            <input type="text" placeholder="Ex: Barre Protéinée Choco" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-1">
              <label className="text-[10px] font-black text-slate-400 uppercase">Catégorie</label>
              <select className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm">
                <option value="snack">Snack</option>
                <option value="boisson">Boisson</option>
                <option value="complement">Complément</option>
              </select>
            </div>
            <div className="space-y-1">
              <label className="text-[10px] font-black text-slate-400 uppercase">Prix de Vente (DH)</label>
              <input type="number" placeholder="0" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
            </div>
          </div>
          <div className="space-y-1">
            <label className="text-[10px] font-black text-slate-400 uppercase">Stock Initial</label>
            <input type="number" placeholder="50" className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center gap-3">
             <Package size={20} className="text-indigo-600" />
             <p className="text-[10px] font-bold text-indigo-700 leading-tight">Ce produit apparaîtra immédiatement dans l'interface de caisse.</p>
          </div>
        </div>
      )
    });
  };

  const handleEditMember = (member: Member) => {
    openModal({
      title: `Modifier : ${member.firstName} ${member.lastName}`,
      type: 'info',
      confirmLabel: "Enregistrer",
      onConfirm: () => alert(`Modifications enregistrées pour ${member.firstName}`),
      content: (
        <div className="grid grid-cols-2 gap-4">
          <div className="space-y-1">
            <label className="text-[10px] font-black uppercase text-slate-400">Prénom</label>
            <input type="text" defaultValue={member.firstName} className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="space-y-1">
            <label className="text-[10px] font-black uppercase text-slate-400">Nom</label>
            <input type="text" defaultValue={member.lastName} className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="col-span-2 space-y-1">
            <label className="text-[10px] font-black uppercase text-slate-400">Téléphone</label>
            <input type="text" defaultValue={member.phone} className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
          <div className="col-span-2 space-y-1">
            <label className="text-[10px] font-black uppercase text-slate-400">Email</label>
            <input type="email" defaultValue={member.email} className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm" />
          </div>
        </div>
      )
    });
  };

  const handleDeleteMember = (member: Member) => {
    openModal({
      title: "Supprimer le membre",
      type: 'warning',
      confirmLabel: "Confirmer la suppression",
      onConfirm: () => alert(`Membre ${member.firstName} supprimé.`),
      content: (
        <div className="space-y-3">
          <p className="text-sm text-slate-500 font-medium leading-relaxed">
            Êtes-vous sûr de vouloir supprimer <span className="font-black text-slate-900">{member.firstName} {member.lastName}</span> ? 
            Toutes ses données d'historique et de paiement seront archivées.
          </p>
          <div className="flex items-center gap-2 p-3 bg-rose-50 text-rose-600 rounded-xl border border-rose-100">
            <ShieldAlert size={18} />
            <span className="text-[10px] font-black uppercase tracking-tight">Action irréversible</span>
          </div>
        </div>
      )
    });
  };

  const handlePrint = (title: string) => {
    openModal({
      title: `Impression : ${title}`,
      type: 'info',
      confirmLabel: "Lancer l'impression",
      onConfirm: () => window.print(),
      content: (
        <div className="text-center py-4 space-y-4">
          <div className="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-300">
            <Printer size={40} />
          </div>
          <p className="text-sm font-medium text-slate-500">Préparation du document pour <span className="font-bold text-slate-900">{title}</span>...</p>
        </div>
      )
    });
  };

  const handleExport = (title: string) => {
    openModal({
      title: `Exportation : ${title}`,
      type: 'success',
      confirmLabel: "Télécharger (CSV)",
      onConfirm: () => alert("Téléchargement démarré..."),
      content: (
        <div className="text-center py-4 space-y-4">
          <div className="h-20 w-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto text-emerald-300">
            <Download size={40} />
          </div>
          <p className="text-sm font-medium text-slate-500">Le fichier CSV pour <span className="font-bold text-slate-900">{title}</span> est prêt.</p>
        </div>
      )
    });
  };

  if (!isLoggedIn) {
    return <LoginView onLogin={() => setIsLoggedIn(true)} />;
  }

  return (
    <div className="flex min-h-screen bg-slate-50">
      <Sidebar activeTab={activeTab} setActiveTab={setActiveTab} />

      <main className="flex-1 min-w-0 overflow-auto">
        <header className="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-20">
          <div className="flex-1 max-w-xl relative group">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors" size={20} />
            <input 
              type="text" 
              placeholder="Rechercher un membre, un paiement..." 
              className="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-transparent focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
          </div>
          <div className="flex items-center gap-4 ml-8">
            <div className="relative" ref={notifRef}>
              <button onClick={() => setIsNotifOpen(!isNotifOpen)} className={`relative text-slate-500 hover:text-slate-900 p-2.5 rounded-xl transition-all ${isNotifOpen ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-slate-50'}`}>
                <Bell size={24} />
                {unreadNotifs > 0 && <span className="absolute top-1 right-1 w-4 h-4 bg-rose-500 text-white text-[10px] font-black flex items-center justify-center rounded-full border-2 border-white animate-bounce">{unreadNotifs}</span>}
              </button>
              {isNotifOpen && <NotificationDropdown onViewAll={() => setActiveTab('notifications')} onClose={() => setIsNotifOpen(false)} />}
            </div>
            <div className="h-10 w-px bg-slate-200 mx-2"></div>
            <div className="relative" ref={profileRef}>
              <button onClick={() => setIsProfileOpen(!isProfileOpen)} className={`flex items-center gap-3 p-1.5 pr-3 rounded-2xl transition-all border border-transparent ${isProfileOpen ? 'bg-white border-slate-100 shadow-sm' : 'hover:bg-slate-50'}`}>
                <div className="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black shadow-md shadow-indigo-100 relative overflow-hidden group">AC</div>
                <div className="text-left hidden sm:block">
                  <p className="text-sm font-black text-slate-900 leading-none">Admin Coach</p>
                  <p className="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tight">Super Admin</p>
                </div>
                <ChevronDown size={16} className={`text-slate-400 transition-transform duration-300 ${isProfileOpen ? 'rotate-180' : ''}`} />
              </button>
              {isProfileOpen && <ProfileDropdown onNavigate={(tab) => setActiveTab(tab)} onClose={() => setIsProfileOpen(false)} />}
            </div>
          </div>
        </header>

        <div className="p-8">
          {activeTab === 'dashboard' && (
            <div className="animate-in fade-in duration-500 space-y-8">
              <div className="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                  <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight">Bonjour, Coach 👋</h1>
                  <p className="text-slate-500 font-medium mt-1">Voici ce qu'il se passe dans votre club aujourd'hui.</p>
                </div>
                <div className="flex items-center gap-3">
                  <div className="flex items-center gap-2 text-sm font-semibold px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 cursor-pointer hover:bg-indigo-100 transition-all" onClick={() => openModal({ title: "Statut Sauna", type: 'info', content: <p className="text-sm font-medium leading-relaxed">Le sauna est actuellement à 85°C. La séance automatique commence dans 15 minutes. Tous les systèmes sont nominaux.</p> })}>
                    <Waves size={16} /> Sauna: Opérationnel 🧖
                  </div>
                  <button onClick={() => setActiveTab('add-member')} className="flex items-center gap-2 text-sm font-bold px-6 py-2 bg-slate-900 text-white rounded-xl shadow-lg shadow-slate-200 hover:bg-slate-800 transition-all active:scale-95">Nouveau Membre</button>
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard title="Total Membres" value={MOCK_STATS.totalMembers} trend={MOCK_STATS.memberTrend} icon={Users} color="bg-indigo-600" />
                <StatCard title="Revenus du Mois" value={MOCK_STATS.monthlyRevenue.toLocaleString('fr-FR')} trend={MOCK_STATS.revenueTrend} icon={CardIcon} color="bg-emerald-500" prefix="DH " />
                <StatCard title="Expirent Bientôt" value={MOCK_STATS.expiringSoon} trend={-5} icon={Timer} color="bg-rose-500" />
                <StatCard title="Membres Fidèles" value={MOCK_STATS.loyalMembers} trend={15} icon={Award} color="bg-amber-500" />
              </div>

              <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div className="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                   <div className="flex items-center justify-between mb-8">
                      <h3 className="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <TrendingUp size={20} className="text-indigo-500" /> Évolution des Revenus
                      </h3>
                      <button onClick={() => openModal({ title: "Détails Financiers", type: 'info', content: <div className="space-y-3"><p className="text-sm font-medium">Croissance constante de +12% par rapport au trimestre précédent.</p><div className="p-3 bg-emerald-50 rounded-xl border border-emerald-100"><p className="text-xs font-bold text-emerald-700">Meilleure journée : 15 Juin (12,400 DH)</p></div></div> })} className="text-xs font-bold text-indigo-600 hover:underline">Détails</button>
                   </div>
                   <div className="h-[300px] w-full">
                    <ResponsiveContainer width="100%" height="100%">
                      <AreaChart data={MOCK_REVENUE}>
                        <defs>
                          <linearGradient id="colorRevenue" x1="0" y1="0" x2="0" y2="1"><stop offset="5%" stopColor="#6366f1" stopOpacity={0.1}/><stop offset="95%" stopColor="#6366f1" stopOpacity={0}/></linearGradient>
                        </defs>
                        <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
                        <XAxis dataKey="month" axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12, fontWeight: 500}} dy={10} />
                        <YAxis axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12, fontWeight: 500}} tickFormatter={(val) => `${val/1000}k`} />
                        <RechartsTooltip contentStyle={{borderRadius: '12px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)'}} itemStyle={{fontSize: '12px', fontWeight: 600}} />
                        <Area type="monotone" dataKey="amount" stroke="#6366f1" strokeWidth={3} fillOpacity={1} fill="url(#colorRevenue)" />
                      </AreaChart>
                    </ResponsiveContainer>
                  </div>
                </div>

                <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                  <h3 className="text-lg font-bold text-slate-900 flex items-center gap-2 mb-8"><BarChart3 size={20} className="text-indigo-500" /> Répartition par Sport</h3>
                  <div className="h-[250px] w-full">
                    <ResponsiveContainer width="100%" height="100%">
                      <BarChart data={MOCK_SPORTS} layout="vertical">
                        <CartesianGrid strokeDasharray="3 3" horizontal={false} stroke="#f1f5f9" />
                        <XAxis type="number" hide />
                        <YAxis dataKey="name" type="category" axisLine={false} tickLine={false} tick={{fill: '#64748b', fontSize: 11, fontWeight: 600}} width={100} />
                        <Bar dataKey="count" radius={[0, 4, 4, 0]} barSize={20}>{MOCK_SPORTS.map((entry, index) => (<Cell key={`cell-${index}`} fill={entry.color} />))}</Bar>
                      </BarChart>
                    </ResponsiveContainer>
                  </div>
                  <button onClick={() => setActiveTab('sports')} className="w-full mt-4 py-2 bg-slate-50 text-slate-500 text-xs font-black rounded-xl hover:bg-slate-100 transition-colors">Gérer les sports</button>
                </div>
              </div>

              <div className="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div className="p-6 border-b border-slate-100 flex items-center justify-between">
                  <h3 className="text-lg font-bold text-slate-900">Alertes Expirations</h3>
                  <button onClick={() => setActiveTab('members')} className="text-sm font-bold text-indigo-600 hover:underline">Voir tout</button>
                </div>
                <div className="overflow-x-auto">
                  <table className="w-full text-left">
                    <thead className="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                      <tr>
                        <th className="px-6 py-4">Membre</th>
                        <th className="px-6 py-4">Sport</th>
                        <th className="px-6 py-4">Date Expiration</th>
                        <th className="px-6 py-4">Contact</th>
                        <th className="px-6 py-4 text-right">Action</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {expiringMembers.map((member) => (
                        <MemberRow 
                          key={member.id} 
                          member={member} 
                          onRenew={handleRenew} 
                          onContact={handleContact} 
                        />
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          )}

          {activeTab === 'members' && (
            <MembersView 
              onAddMember={() => setActiveTab('add-member')} 
              onRenew={handleRenew}
              onEdit={handleEditMember}
              onDelete={handleDeleteMember}
              onPrint={() => handlePrint("Liste des membres")}
              onExport={() => handleExport("Membres_NEEDSPORT")}
              onPrintReceipt={handlePrintReceipt}
            />
          )}
          {activeTab === 'sports' && (
            <ActivitiesView 
              onAddActivity={() => setActiveTab('add-activity')}
              onEdit={(activity) => openModal({ title: `Modifier : ${activity.name}`, type: 'info', confirmLabel: "Enregistrer", onConfirm: () => alert("Sport modifié"), content: <div className="space-y-4"><div className="space-y-1"><label className="text-[10px] font-black text-slate-400 uppercase">Nom</label><input type="text" defaultValue={activity.name} className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold" /></div><div className="space-y-1"><label className="text-[10px] font-black text-slate-400 uppercase">Prix (DH)</label><input type="number" defaultValue={activity.monthlyPrice} className="w-full p-3 bg-slate-50 border border-slate-100 rounded-xl font-bold" /></div></div> })}
              onDelete={(activity) => openModal({ title: "Supprimer l'activité", type: 'warning', confirmLabel: "Supprimer", onConfirm: () => alert("Activité supprimée"), content: <p className="text-sm font-medium">Supprimer <span className="font-black text-slate-900">{activity.name}</span> ? Cette action affectera les futurs abonnements.</p> })}
            />
          )}
          {activeTab === 'schedule' && (
            <ScheduleView 
              onModify={handleModifySchedule}
            />
          )}
          {activeTab === 'financials' && (
            <FinancialsView 
              onAddExpense={handleAddExpense}
              onExport={() => handleExport("Rapport_Financier_2024")}
              onViewExpense={handleViewExpense}
            />
          )}
          {activeTab === 'staff' && (
            <StaffView 
              onAddStaff={handleAddStaff}
              onEditStaff={handleEditStaff}
              onDeleteStaff={handleDeleteStaff}
            />
          )}
          {activeTab === 'pos' && (
            <POSView 
              onCheckout={handleCheckoutPOS}
              onAddItem={handleAddItemPOS}
            />
          )}
          {activeTab === 'payments' && (
            <PaymentsView 
              onPrint={() => handlePrint("Journal des paiements")}
              onExport={() => handleExport("Paiements_2024")}
            />
          )}
          {activeTab === 'settings' && <SettingsView />}
          {activeTab === 'notifications' && <NotificationsView />}
          {activeTab === 'add-member' && <AddMemberView onBack={() => setActiveTab('members')} />}
          {activeTab === 'add-activity' && <AddActivityView onBack={() => setActiveTab('sports')} />}
        </div>
      </main>

      <QuickActionModal 
        isOpen={modalConfig.isOpen} 
        onClose={closeModal} 
        title={modalConfig.title} 
        type={modalConfig.type}
        onConfirm={modalConfig.onConfirm}
        confirmLabel={modalConfig.confirmLabel}
      >
        {modalConfig.content}
      </QuickActionModal>
    </div>
  );
};

export default App;
