
import React, { useState, useEffect } from 'react';
import { 
  Settings, 
  Palette, 
  CreditCard, 
  Bell, 
  Shield, 
  Globe, 
  Image as ImageIcon, 
  Check,
  Save,
  Smartphone,
  MessageSquare,
  Lock,
  Trophy,
  Waves,
  Languages,
  User,
  Camera,
  MapPin,
  Mail,
  AlertCircle
} from 'lucide-react';
import { useTheme } from '../context/ThemeContext';

type SettingsSection = 'profile' | 'general' | 'branding' | 'payments' | 'notifications' | 'security';

const SettingsView: React.FC = () => {
  const { setThemeColor: updateGlobalTheme } = useTheme();
  const [activeSection, setActiveSection] = useState<SettingsSection>('profile');
  const [hasChanges, setHasChanges] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const [saveMessage, setSaveMessage] = useState('');
  const [saveError, setSaveError] = useState('');
  
  // Profile state
  const [profileName, setProfileName] = useState('Admin Coach');
  const [profileEmail, setProfileEmail] = useState('super-admin@needsport.ma');
  const [profileCity, setProfileCity] = useState('Casablanca, Maroc');
  
  // General settings state
  const [clubName, setClubName] = useState('NEEDSPORT Pro');
  const [slogan, setSlogan] = useState('La performance au quotidien');
  const [themeColor, setThemeColor] = useState('indigo');
  const [language, setLanguage] = useState('fr');
  const [timezone, setTimezone] = useState('(GMT+01:00) Casablanca');
  
  // Payment settings state
  const [currency, setCurrency] = useState('DH');
  const [taxRate, setTaxRate] = useState('20');

  // Load settings on component mount
  useEffect(() => {
    loadSettings();
  }, []);

  const loadSettings = async () => {
    try {
      const response = await fetch('../Backend/api/settings.php?action=all');
      if (response.ok) {
        const data = await response.json();
        
        // Load general settings
        if (data.general) {
          setClubName(data.general.clubName || 'NEEDSPORT Pro');
          setSlogan(data.general.slogan || 'La performance au quotidien');
          setLanguage(data.general.language || 'fr');
          setTimezone(data.general.timezone || '(GMT+01:00) Casablanca');
        }
        
        // Load theme settings
        if (data.theme) {
          setThemeColor(data.theme.themeColor || 'indigo');
        }
        
        // Load profile info
        if (data.profile) {
          setProfileName(data.profile.name || 'Admin Coach');
          setProfileEmail(data.profile.email || 'super-admin@needsport.ma');
          setProfileCity(data.profile.city || 'Casablanca, Maroc');
        }
        
        // Load payment settings
        if (data.payments) {
          setCurrency(data.payments.currency || 'DH');
          setTaxRate(String(data.payments.taxRate) || '20');
        }
      }
    } catch (error) {
      console.error('Failed to load settings:', error);
    }
  };

  const navItems = [
    { id: 'profile', icon: User, label: 'Mon Profil' },
    { id: 'general', icon: Globe, label: 'Général' },
    { id: 'branding', icon: Palette, label: 'Branding & Design' },
    { id: 'payments', icon: CreditCard, label: 'Paiements & Taxes' },
    { id: 'notifications', icon: Bell, label: 'Notifications' },
    { id: 'security', icon: Shield, label: 'Accès & Sécurité' },
  ];

  const handleSave = async () => {
    setIsSaving(true);
    setSaveMessage('');
    setSaveError('');

    try {
      let response;
      
      // Save based on active section
      if (activeSection === 'profile') {
        response = await fetch('../Backend/api/settings.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'save_profile',
            name: profileName,
            email: profileEmail,
            city: profileCity,
          }),
        });
      } else if (activeSection === 'general') {
        response = await fetch('../Backend/api/settings.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'save_general',
            clubName,
            slogan,
            language,
            timezone,
          }),
        });
      } else if (activeSection === 'branding') {
        response = await fetch('../Backend/api/settings.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'save_branding',
            themeColor,
          }),
        });
        
        // Apply theme color globally
        updateGlobalTheme(themeColor);
      } else if (activeSection === 'payments') {
        response = await fetch('../Backend/api/settings.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            action: 'save_payments',
            currency,
            taxRate: parseFloat(taxRate),
          }),
        });
      }

      if (response && response.ok) {
        const result = await response.json();
        setSaveMessage(result.message || 'Paramètres enregistrés avec succès !');
        setHasChanges(false);
        setTimeout(() => setSaveMessage(''), 3000);
      } else {
        throw new Error('Failed to save settings');
      }
    } catch (error) {
      setSaveError('Erreur lors de l\'enregistrement des paramètres');
      console.error(error);
    } finally {
      setIsSaving(false);
    }
  };

  const ColorPicker = ({ color }: { color: string }) => (
    <button 
      onClick={() => {setThemeColor(color); setHasChanges(true);}}
      className={`h-10 w-10 rounded-full border-4 transition-all ${
        themeColor === color ? 'border-slate-900 scale-110' : 'border-transparent'
      } ${
        color === 'indigo' ? 'bg-indigo-600' : 
        color === 'rose' ? 'bg-rose-500' : 
        color === 'emerald' ? 'bg-emerald-500' : 
        color === 'amber' ? 'bg-amber-500' : 'bg-slate-900'
      }`}
    />
  );

  return (
    <div className="animate-in fade-in slide-in-from-bottom-4 duration-500 max-w-6xl mx-auto pb-24">
      <div className="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <Settings className="text-slate-400" size={32} />
            Paramètres Système
          </h1>
          <p className="text-slate-500 font-medium mt-1">Configurez votre environnement de gestion NEEDSPOORT</p>
        </div>
        {hasChanges && (
          <button 
            onClick={handleSave}
            disabled={isSaving}
            className="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-black rounded-2xl hover:bg-indigo-700 disabled:opacity-50 transition-all shadow-xl shadow-indigo-100 animate-in zoom-in"
          >
            <Save size={20} />
            {isSaving ? 'Enregistrement...' : 'Enregistrer les modifications'}
          </button>
        )}
      </div>

      {saveMessage && (
        <div className="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 animate-in fade-in">
          <Check className="text-emerald-600" size={20} />
          <p className="text-emerald-700 font-bold">{saveMessage}</p>
        </div>
      )}

      {saveError && (
        <div className="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 animate-in fade-in">
          <AlertCircle className="text-rose-600" size={20} />
          <p className="text-rose-700 font-bold">{saveError}</p>
        </div>
      )}

      <div className="flex flex-col lg:flex-row gap-8">
        {/* Sub Navigation */}
        <div className="lg:w-64 space-y-1">
          {navItems.map((item) => (
            <button
              key={item.id}
              onClick={() => setActiveSection(item.id as SettingsSection)}
              className={`w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition-all font-bold text-sm ${
                activeSection === item.id 
                  ? 'bg-white text-indigo-600 shadow-sm border border-slate-100' 
                  : 'text-slate-500 hover:bg-white/50 hover:text-slate-900'
              }`}
            >
              <item.icon size={20} />
              {item.label}
            </button>
          ))}
        </div>

        {/* Content Area */}
        <div className="flex-1 space-y-8">
          {/* Profile Section */}
          {activeSection === 'profile' && (
            <div className="space-y-6 animate-in fade-in duration-300">
              <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 className="text-lg font-black text-slate-900 mb-8 flex items-center gap-2">
                  <User size={20} className="text-indigo-600" />
                  Informations du Profil
                </h3>
                
                <div className="flex flex-col md:flex-row items-start gap-8">
                  <div className="relative group">
                    <div className="h-32 w-32 rounded-3xl bg-indigo-600 flex items-center justify-center text-white text-4xl font-black shadow-xl shadow-indigo-100 relative overflow-hidden">
                      AC
                      <div className="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <Camera size={24} />
                      </div>
                    </div>
                    <p className="text-center mt-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID: #ADMIN-01</p>
                  </div>
                  
                  <div className="flex-1 w-full space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div className="space-y-2">
                        <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Prénom & Nom</label>
                        <div className="relative">
                          <User size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" />
                          <input 
                            type="text" 
                            value={profileName}
                            onChange={(e) => {setProfileName(e.target.value); setHasChanges(true);}}
                            className="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                          />
                        </div>
                      </div>
                      <div className="space-y-2">
                        <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Poste / Rôle</label>
                        <input 
                          type="text" 
                          defaultValue="Super Administrateur"
                          className="w-full px-5 py-3.5 bg-slate-100 border border-slate-200 rounded-2xl outline-none font-bold text-slate-400 cursor-not-allowed"
                          disabled
                        />
                      </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div className="space-y-2">
                        <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Email</label>
                        <div className="relative">
                          <Mail size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" />
                          <input 
                            type="email" 
                            value={profileEmail}
                            onChange={(e) => {setProfileEmail(e.target.value); setHasChanges(true);}}
                            className="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                          />
                        </div>
                      </div>
                      <div className="space-y-2">
                        <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Ville</label>
                        <div className="relative">
                          <MapPin size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" />
                          <input 
                            type="text" 
                            value={profileCity}
                            onChange={(e) => {setProfileCity(e.target.value); setHasChanges(true);}}
                            className="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* General Section */}
          {activeSection === 'general' && (
            <div className="space-y-6">
              <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 className="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                  <Globe size={20} className="text-indigo-500" />
                  Informations du Club
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="space-y-2">
                    <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Nom du Club</label>
                    <input 
                      type="text" 
                      className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                      value={clubName}
                      onChange={(e) => {setClubName(e.target.value); setHasChanges(true);}}
                    />
                  </div>
                  <div className="space-y-2">
                    <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Slogan / Sous-titre</label>
                    <input 
                      type="text" 
                      value={slogan}
                      onChange={(e) => {setSlogan(e.target.value); setHasChanges(true);}}
                      className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                    />
                  </div>
                </div>
              </div>

              <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 className="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                  <Languages size={20} className="text-emerald-500" />
                  Langue & Localisation
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="space-y-2">
                    <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Langue de l'interface</label>
                    <select 
                      className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                      value={language}
                      onChange={(e) => {setLanguage(e.target.value); setHasChanges(true);}}
                    >
                      <option value="fr">🇫🇷 Français (Maroc)</option>
                      <option value="en">🇺🇸 English</option>
                      <option value="ar">🇲🇦 العربية</option>
                    </select>
                  </div>
                  <div className="space-y-2">
                    <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Fuseau horaire</label>
                    <select 
                      className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                      value={timezone}
                      onChange={(e) => {setTimezone(e.target.value); setHasChanges(true);}}
                    >
                      <option value="(GMT+01:00) Casablanca">(GMT+01:00) Casablanca</option>
                      <option value="(GMT+00:00) London">(GMT+00:00) London</option>
                      <option value="(GMT+01:00) Paris">(GMT+01:00) Paris</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Branding Section */}
          {activeSection === 'branding' && (
            <div className="space-y-6">
              <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 className="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                  <ImageIcon size={20} className="text-rose-500" />
                  Logo du Club
                </h3>
                <div className="flex items-center gap-8">
                  <div className="h-32 w-32 rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 hover:border-indigo-400 hover:bg-indigo-50 transition-all cursor-pointer group">
                    <Trophy size={40} className="group-hover:scale-110 transition-transform" />
                    <span className="text-[10px] font-black mt-2">LOGO</span>
                  </div>
                  <div className="space-y-3">
                    <p className="text-sm font-bold text-slate-700">Téléchargez votre logo</p>
                    <p className="text-xs text-slate-400 leading-relaxed max-w-xs">
                      Recommandé : PNG transparent, 512x512px minimum. Ce logo apparaîtra sur les factures et le dashboard.
                    </p>
                    <button className="px-4 py-2 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-slate-800 transition-colors">
                      Choisir un fichier
                    </button>
                  </div>
                </div>
              </div>

              <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 className="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                  <Palette size={20} className="text-indigo-500" />
                  Couleur Thème
                </h3>
                <div className="space-y-6">
                  <div className="flex gap-4">
                    <ColorPicker color="indigo" />
                    <ColorPicker color="rose" />
                    <ColorPicker color="emerald" />
                    <ColorPicker color="amber" />
                    <ColorPicker color="slate" />
                  </div>
                  <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div className="flex items-center gap-3">
                      <div className={`h-8 w-8 rounded-lg ${themeColor === 'indigo' ? 'bg-indigo-600' : themeColor === 'rose' ? 'bg-rose-500' : themeColor === 'emerald' ? 'bg-emerald-500' : 'bg-amber-500'}`}></div>
                      <p className="text-sm font-bold text-slate-600 italic">Prévisualisation de la couleur accentuée</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Payments Section */}
          {activeSection === 'payments' && (
            <div className="space-y-6">
              <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 className="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                  <CreditCard size={20} className="text-emerald-500" />
                  Configuration Financière
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="space-y-2">
                    <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Devise Principale</label>
                    <select 
                      className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                      value={currency}
                      onChange={(e) => {setCurrency(e.target.value); setHasChanges(true);}}
                    >
                      <option value="DH">Dirham Marocain (DH)</option>
                      <option value="EUR">Euro (€)</option>
                      <option value="USD">US Dollar ($)</option>
                    </select>
                  </div>
                  <div className="space-y-2">
                    <label className="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Taux de TVA (%)</label>
                    <input 
                      type="number" 
                      value={taxRate}
                      onChange={(e) => {setTaxRate(e.target.value); setHasChanges(true);}}
                      className="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"
                    />
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* Notifications Section */}
          {activeSection === 'notifications' && (
            <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-8">
              <h3 className="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                <Bell size={20} className="text-amber-500" />
                Alertes & Communications
              </h3>
              
              <div className="space-y-6">
                <div className="flex items-start gap-4 p-6 bg-indigo-50 rounded-3xl border border-indigo-100">
                  <div className="p-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-200">
                    <MessageSquare size={24} />
                  </div>
                  <div className="flex-1">
                    <div className="flex items-center justify-between mb-1">
                      <h4 className="text-sm font-black text-indigo-900 uppercase tracking-tight">WhatsApp Automatisation</h4>
                      <span className="px-2 py-0.5 bg-indigo-200 text-indigo-700 text-[10px] font-black rounded-full uppercase">Premium</span>
                    </div>
                    <p className="text-xs text-indigo-700/70 font-medium leading-relaxed">
                      Envoyez automatiquement les rappels d'expiration et les factures par WhatsApp.
                    </p>
                  </div>
                  <label className="relative inline-flex items-center cursor-pointer mt-2">
                    <input type="checkbox" className="sr-only peer" />
                    <div className="w-11 h-6 bg-indigo-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                  </label>
                </div>
              </div>
            </div>
          )}

          {/* Security Section */}
          {activeSection === 'security' && (
            <div className="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
              <h3 className="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                <Shield size={20} className="text-indigo-600" />
                Accès & Sécurité
              </h3>
              <div className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-indigo-200 transition-colors cursor-pointer">
                    <div className="flex items-center gap-3">
                      <Lock size={20} className="text-slate-400 group-hover:text-indigo-500 transition-colors" />
                      <div>
                        <p className="text-sm font-black text-slate-900">Mot de passe</p>
                        <p className="text-[10px] font-bold text-slate-400 uppercase">Dernière modif: Il y a 15j</p>
                      </div>
                    </div>
                    <span className="text-xs font-black text-indigo-600">Modifier</span>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default SettingsView;
