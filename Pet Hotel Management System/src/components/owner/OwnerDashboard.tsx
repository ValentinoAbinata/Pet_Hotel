import { useState } from 'react';
import { User } from '../../App';
import { OwnerHome } from './OwnerHome';
import { ReservationForm } from './ReservationForm';
import { PetMonitoring } from './PetMonitoring';
import { NotificationCenter } from './NotificationCenter';
import { Home, Calendar, Activity, Bell, LogOut, Menu, X } from 'lucide-react';
import { Button } from '../ui/button';

interface OwnerDashboardProps {
  user: User;
  onLogout: () => void;
}

type OwnerTab = 'home' | 'reservation' | 'monitoring' | 'notifications';

export function OwnerDashboard({ user, onLogout }: OwnerDashboardProps) {
  const [activeTab, setActiveTab] = useState<OwnerTab>('home');
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const tabs = [
    { id: 'home' as OwnerTab, label: 'Beranda', icon: Home },
    { id: 'reservation' as OwnerTab, label: 'Reservasi', icon: Calendar },
    { id: 'monitoring' as OwnerTab, label: 'Monitoring', icon: Activity },
    { id: 'notifications' as OwnerTab, label: 'Notifikasi', icon: Bell },
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Mobile Header */}
      <div className="lg:hidden bg-white border-b sticky top-0 z-50">
        <div className="flex items-center justify-between p-4">
          <h1 className="text-lg">Pet Hotel</h1>
          <div className="flex items-center gap-2">
            <Button 
              variant="ghost" 
              size="sm"
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            >
              {mobileMenuOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
            </Button>
          </div>
        </div>
        
        {mobileMenuOpen && (
          <div className="border-t p-4 space-y-2">
            <div className="text-sm text-gray-600 mb-2">
              Hai, {user.name}
            </div>
            <Button 
              variant="outline" 
              size="sm" 
              onClick={onLogout}
              className="w-full"
            >
              <LogOut className="w-4 h-4 mr-2" />
              Keluar
            </Button>
          </div>
        )}
      </div>

      <div className="flex flex-col lg:flex-row">
        {/* Desktop Sidebar */}
        <div className="hidden lg:block w-64 bg-white border-r min-h-screen sticky top-0">
          <div className="p-6">
            <h1 className="text-xl bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
              Pet Hotel
            </h1>
            <p className="text-sm text-gray-600 mt-1">Hai, {user.name}</p>
          </div>
          
          <nav className="px-3 space-y-1">
            {tabs.map((tab) => {
              const Icon = tab.icon;
              return (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={`w-full flex items-center gap-3 px-3 py-2 rounded-lg transition-colors ${
                    activeTab === tab.id
                      ? 'bg-blue-50 text-blue-600'
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <Icon className="w-5 h-5" />
                  <span>{tab.label}</span>
                </button>
              );
            })}
          </nav>

          <div className="absolute bottom-0 left-0 right-0 p-4 border-t">
            <Button variant="outline" onClick={onLogout} className="w-full">
              <LogOut className="w-4 h-4 mr-2" />
              Keluar
            </Button>
          </div>
        </div>

        {/* Main Content */}
        <div className="flex-1">
          <div className="max-w-7xl mx-auto">
            {activeTab === 'home' && <OwnerHome />}
            {activeTab === 'reservation' && <ReservationForm />}
            {activeTab === 'monitoring' && <PetMonitoring />}
            {activeTab === 'notifications' && <NotificationCenter />}
          </div>
        </div>
      </div>

      {/* Mobile Bottom Navigation */}
      <div className="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t z-40">
        <div className="grid grid-cols-4 gap-1 p-2">
          {tabs.map((tab) => {
            const Icon = tab.icon;
            return (
              <button
                key={tab.id}
                onClick={() => {
                  setActiveTab(tab.id);
                  setMobileMenuOpen(false);
                }}
                className={`flex flex-col items-center gap-1 py-2 rounded-lg transition-colors ${
                  activeTab === tab.id
                    ? 'text-blue-600'
                    : 'text-gray-600'
                }`}
              >
                <Icon className="w-5 h-5" />
                <span className="text-xs">{tab.label}</span>
              </button>
            );
          })}
        </div>
      </div>

      {/* Add padding bottom for mobile nav */}
      <div className="lg:hidden h-20" />
    </div>
  );
}
